# Variables de la configuración de Cloudflare.
# Definir en terraform.tfvars (NO versionar) o como variables de entorno
# TF_VAR_*. El api_token debe ser un token con permisos mínimos:
#   Zone:Read, DNS:Edit, Zone Settings:Edit, Zone WAF:Edit, Page Rules:Edit.

variable "cloudflare_api_token" {
  type        = string
  sensitive   = true
  description = "Token de API de Cloudflare con permisos mínimos."
}

variable "zone_id" {
  type        = string
  description = "Zone ID del dominio en Cloudflare."
}

variable "zone_name" {
  type        = string
  description = "Nombre del dominio (ej. sdbiosensor.com.pa)."
}

variable "origin_ip" {
  type        = string
  description = "IP pública del origen (balanceador / servidor de la app)."
}
