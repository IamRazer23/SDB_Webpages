# ============================================================
# SD Biosensor Panamá — Cloudflare como código (Terraform)
# DNS · CDN · SSL/TLS Full(Strict) · WAF · Rate Limit · Headers · HTTP/3
# Provider: cloudflare ~> 4.x
# Aplicar:  terraform init && terraform plan && terraform apply
# ============================================================

terraform {
  required_providers {
    cloudflare = {
      source  = "cloudflare/cloudflare"
      version = "~> 4.40"
    }
  }
}

provider "cloudflare" {
  api_token = var.cloudflare_api_token
}

# --- DNS (apex + www → origen) ------------------------------------
resource "cloudflare_record" "apex" {
  zone_id = var.zone_id
  name    = "@"
  type    = "A"
  content = var.origin_ip
  proxied = true # naranja: tráfico por la red de Cloudflare (CDN+WAF+DDoS)
  ttl     = 1
}

resource "cloudflare_record" "www" {
  zone_id = var.zone_id
  name    = "www"
  type    = "CNAME"
  content = var.zone_name
  proxied = true
  ttl     = 1
}

# --- SSL/TLS, HSTS, HTTP/3, Brotli, 0-RTT, min TLS 1.3 ------------
resource "cloudflare_zone_settings_override" "settings" {
  zone_id = var.zone_id
  settings {
    ssl                      = "strict" # Full (Strict): valida cert del origen
    always_use_https         = "on"
    min_tls_version          = "1.3"
    tls_1_3                  = "on"
    automatic_https_rewrites = "on"
    opportunistic_encryption = "on"
    brotli                   = "on"
    http3                    = "on"
    zero_rtt                 = "on"
    early_hints              = "on"
    websockets               = "on"
    security_header {
      enabled            = true # HSTS
      include_subdomains = true
      max_age            = 31536000
      preload            = true
      nosniff            = true
    }
  }
}

# --- Cache de estáticos (imágenes, CSS, JS con hash de Vite) ------
resource "cloudflare_ruleset" "cache_rules" {
  zone_id = var.zone_id
  name    = "cache-static-assets"
  kind    = "zone"
  phase   = "http_request_cache_settings"

  rules {
    action      = "set_cache_settings"
    description = "Cache agresivo de /build/* y estáticos"
    expression  = "(http.request.uri.path matches \"^/build/\") or (http.request.uri.path.extension in {\"css\" \"js\" \"png\" \"jpg\" \"jpeg\" \"webp\" \"avif\" \"svg\" \"woff2\"})"
    enabled     = true
    action_parameters {
      cache = true
      edge_ttl {
        mode    = "override_origin"
        default = 31536000 # 1 año (assets versionados por hash)
      }
      browser_ttl { mode = "override_origin", default = 31536000 }
    }
  }
}

# --- Cabeceras de seguridad en el borde (respaldo del middleware) -
resource "cloudflare_ruleset" "security_headers" {
  zone_id = var.zone_id
  name    = "security-response-headers"
  kind    = "zone"
  phase   = "http_response_headers_transform"

  rules {
    action      = "rewrite"
    description = "Headers de seguridad globales"
    expression  = "true"
    enabled     = true
    action_parameters {
      headers {
        name      = "X-Frame-Options"
        operation = "set"
        value     = "DENY"
      }
      headers {
        name      = "X-Content-Type-Options"
        operation = "set"
        value     = "nosniff"
      }
      headers {
        name      = "Referrer-Policy"
        operation = "set"
        value     = "strict-origin-when-cross-origin"
      }
      headers {
        name      = "Permissions-Policy"
        operation = "set"
        value     = "geolocation=(), microphone=(), camera=()"
      }
    }
  }
}

# --- WAF gestionado (OWASP Core Ruleset) -------------------------
resource "cloudflare_ruleset" "waf_managed" {
  zone_id = var.zone_id
  name    = "waf-managed"
  kind    = "zone"
  phase   = "http_request_firewall_managed"

  rules {
    action      = "execute"
    description = "Cloudflare Managed Ruleset"
    expression  = "true"
    enabled     = true
    action_parameters {
      id = "efb7b8c949ac4650a09736fc376e9aee" # Cloudflare Managed Ruleset
    }
  }
  rules {
    action      = "execute"
    description = "OWASP Core Ruleset"
    expression  = "true"
    enabled     = true
    action_parameters {
      id = "4814384a9e5d4991b9815dcfc25d2f1f" # OWASP Core Ruleset
    }
  }
}

# --- Rate limiting (anti fuerza bruta / DoS sobre el buscador) ---
resource "cloudflare_ruleset" "rate_limit" {
  zone_id = var.zone_id
  name    = "rate-limit"
  kind    = "zone"
  phase   = "http_ratelimit"

  rules {
    action      = "block"
    description = "Limita ráfagas por IP en el buscador"
    expression  = "(http.request.uri.path eq \"/productos\" or http.request.uri.path eq \"/soporte\")"
    enabled     = true
    ratelimit {
      characteristics     = ["ip.src", "cf.colo.id"]
      period              = 60
      requests_per_period = 60
      mitigation_timeout  = 600
    }
  }
}

# --- Bot Fight Mode ----------------------------------------------
resource "cloudflare_bot_management" "bots" {
  zone_id    = var.zone_id
  fight_mode = true
}
