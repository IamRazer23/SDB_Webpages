# Migración SD Biosensor Panamá → Cloudflare + Supabase + Sentry

> Documento técnico único: auditoría, runbook sin interrupciones, respaldo/recuperación,
> escalabilidad, costos, checklist de seguridad y comparativa antes/después.

---

## 0. Calibración honesta del alcance (leer primero)

La app real es un **sitio corporativo de catálogo de solo lectura** (Laravel 13, 6 rutas `GET`,
sin login, sin formularios de escritura, sin API, sin uploads activos; el contenido sale de
seeders). Por tanto:

| Componente pedido | Aplicación real |
|---|---|
| **Supabase PostgreSQL** | ✅ Migración directa y de alto valor (Laravel soporta `pgsql` nativo). |
| **Supabase Storage** | ✅ Se prepara para imágenes/PDF (hoy `image_path`/`file_path` están en `null`). |
| **Cloudflare (DNS/CDN/WAF/TLS)** | ✅ Aplica 100%; máxima palanca de rendimiento y defensa. |
| **Sentry** | ✅ Aplica 100% (backend Laravel + browser JS). |
| **Supabase Auth / RLS / Realtime** | ⚠️ **No hay usuarios ni datos por-usuario que migrar.** Se deja la *base* (RLS activada, policies de solo-lectura pública, Auth listo) para cuando exista panel/admin. Migrar "usuarios existentes" sería ficticio: hoy son cero. |

Esto **no** reduce la seguridad: al contrario, evita controles cosméticos y prioriza los
riesgos reales (debug en prod, TLS, headers, secretos).

---

## 1. Auditoría inicial (resumen)

Arquitectura actual: monolito Laravel (Blade SSR) + Nginx + PHP-FPM + MySQL 8, un solo nodo
Docker Compose. Sin DNS gestionado, sin CDN, sin TLS, sin observabilidad.

| Categoría | Hallazgo | Severidad |
|---|---|---|
| Config | `APP_DEBUG=true`/`APP_ENV=local` en contenedor | Crítico en prod |
| Transporte | Sin HTTPS/TLS ni HSTS (Nginx solo `:80`) | Alto |
| Headers | Sin CSP/X-Frame-Options/nosniff (Clickjacking) | Alto |
| Secretos | Credenciales en `docker-compose.yml` versionado | Alto |
| Exposición | Puerto MySQL `3306` publicado al host | Medio |
| DoS | Sin rate limiting; buscador `LIKE %term%` sin índice | Medio |
| Contenedor | Corre como root; `chmod 777`; imagen single-stage | Medio |
| Perf | Faltan índices compuestos | Medio |
| DevSecOps | Sin CI/CD, SAST, escaneo de dependencias | Medio |
| Observabilidad | Logs no centralizados, sin alertas | Bajo |

Limpio y correcto: Eloquent parametrizado (sin SQLi), Blade auto-escapa (sin XSS por output),
`.env`/`.git` fuera de git e imagen, sin raw SQL, sin `eval`/shell.

---

## 2. Migración a Cloudflare (sin interrupción)

IaC en [infra/cloudflare/main.tf](../infra/cloudflare/main.tf). Orden seguro:

1. **Crear la zona en Cloudflare** e importar registros DNS existentes **antes** de cambiar
   nameservers (modo *review*). Verificar que cada A/CNAME/MX/TXT coincide con el actual.
2. **Bajar TTL** del DNS antiguo a 300s 24–48h antes del corte (rollback rápido).
3. **`proxied = false` (DNS-only)** al inicio → valida que Cloudflare resuelve igual que hoy,
   sin meter el proxy. Probar el sitio.
4. **Cambiar nameservers** al registrador. Propagación sin downtime (ambos resuelven al mismo origen).
5. **Activar proxy (`proxied = true`)** registro por registro → entra CDN + WAF + DDoS.
6. **SSL/TLS = Full (Strict)** con certificado válido en el origen (Let's Encrypt en Nginx, §runbook).
   Activar `min_tls_version=1.3`, HSTS, Brotli, HTTP/3, Early Hints, 0-RTT (ya en `main.tf`).
7. **Cache**: assets `/build/*` y estáticos con `edge_ttl` 1 año (versionados por hash de Vite).
   Para HTML, añadir `spatie/laravel-responsecache` + `Cache-Control` y una *cache rule* de
   página completa (el catálogo es ~99% cacheable).
8. **WAF** (Managed + OWASP Core Ruleset), **Rate Limiting** (60 req/min en `/productos`,`/soporte`)
   y **Bot Fight Mode**: ya declarados en `main.tf`.

Rollback: revertir nameservers o poner `proxied=false`; el origen nunca cambió.

---

## 3. Migración a Supabase (PostgreSQL)

Laravel soporta Postgres nativamente: las migraciones existentes son agnósticas de motor.

**Esquema** (fuente de verdad = migraciones Laravel):
```bash
# En un proyecto Supabase nuevo:
cp .env.production.example .env        # DB_CONNECTION=pgsql → host/pooler Supabase
php artisan migrate --force            # crea el esquema en Postgres
php artisan migrate --path=database/migrations/2026_06_20_000001_add_performance_indexes.php
```

**Capa específica de Supabase** (RLS, índices de búsqueda trgm, triggers):
aplicar [supabase/migrations/0001_supabase_layer.sql](../supabase/migrations/0001_supabase_layer.sql)
y [0002_storage.sql](../supabase/migrations/0002_storage.sql) vía SQL Editor o `supabase db push`.

**Datos** (cero pérdida):
- Si los datos == seeders → `php artisan db:seed --force` (reproducible, sin herramientas).
- Si hay contenido real → `pgloader supabase/migrate-data.load`
  ([config](../supabase/migrate-data.load), carga *data-only* + resync de secuencias).

**Optimización aplicada**: índices compuestos (filtros reales de los controladores) + GIN
`pg_trgm` para el buscador (sustituye `LIKE %x%` por `ILIKE` indexado). Constraints y FKs
preservados (`on delete cascade`), `json`→`jsonb`.

**Seguridad (RLS)**: activada en todas las tablas. `anon`/`authenticated` solo **leen** filas
activas; toda escritura va por la conexión privilegiada de Laravel o `service_role`. Modelo de
acceso mínimo necesario.

**Storage**: hoy las imágenes/archivos de contenido se sirven desde el repo
(`public/media/`, resueltas con `media_url()` — ver [public/media/README.md](../public/media/README.md)).
Si se migra a Supabase Storage (buckets `media`/`documents`, lectura pública), basta con
guardar la **URL absoluta** del objeto en la columna (`image_path`/`file_path`): `media_url()`
detecta `http(s)://` y la devuelve tal cual, sin tocar las vistas.

**Auth (foundation)**: cuando exista admin, usar **Laravel Fortify/Breeze** (sesión server-side,
encaja con SSR) para login/registro/recuperación/MFA TOTP; Supabase Auth queda como opción si
se construye una SPA separada. Hashing: bcrypt (`BCRYPT_ROUNDS=12`, ya configurado) o Argon2id.

---

## 4. Sentry (observabilidad)

```bash
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn=<DSN>
```
Wiring de excepciones en `bootstrap/app.php`:
```php
->withExceptions(function (Exceptions $exceptions) {
    \Sentry\Laravel\Integration::handles($exceptions);
})
```
Config ya provista en [config/sentry.php](../config/sentry.php) y variables en
`.env.production.example`. Frontend: `@sentry/browser` con `Sentry.init({ dsn, tracesSampleRate,
replaysSessionSampleRate })` en `resources/js/app.js` (Session Replay + RUM).

| Capacidad | Cómo |
|---|---|
| Error tracking (back/front/API/DB) | SDK Laravel + browser; breadcrumbs SQL activados |
| Performance | `traces_sample_rate=0.1`; detecta consultas lentas y N+1 |
| Session Replay | `@sentry/browser` replays |
| Release tracking | `SENTRY_RELEASE` inyectado por CI ([ci-cd.yml](../.github/workflows/ci-cd.yml)) |
| Alertas | Reglas Sentry → Slack/Email: errores críticos, picos de latencia, fallos de auth |

Privacidad: `send_default_pii=false`, `sql_bindings=false` (no se envían valores → anti fuga de datos).

---

## 5. Checklist de seguridad (OWASP)

- [x] **Inyección SQL** — Eloquent parametrizado; RLS; sin raw SQL.
- [x] **XSS** — Blade auto-escapa; CSP en middleware + borde Cloudflare.
- [x] **CSRF** — sin escrituras hoy; Laravel `@csrf` al añadir formularios.
- [x] **Clickjacking** — `X-Frame-Options: DENY` + `frame-ancestors 'none'`.
- [x] **SSRF** — sin fetch de URLs de usuario (validar si se añade).
- [x] **TLS** — Full(Strict) + TLS 1.3 + HSTS preload.
- [x] **WAF / DDoS / Bots** — Cloudflare Managed + OWASP CRS + Bot Fight.
- [x] **Rate limiting / fuerza bruta** — borde Cloudflare + `throttle` en rutas.
- [x] **Secret management** — `.env` fuera de git/imagen; Vault/Secrets Manager; rotación.
- [x] **Headers** — CSP, nosniff, Referrer-Policy, Permissions-Policy.
- [x] **Dependencias** — `composer/npm audit` + Trivy en CI.
- [x] **Auditoría de accesos** — logs Cloudflare/WAF + Sentry + alertas.
- [ ] **MFA/RBAC/credential stuffing** — al implementar autenticación (Fortify + spatie/permission).

---

## 6. Respaldo y recuperación

| Activo | Estrategia | RPO / RTO |
|---|---|---|
| BD (Supabase) | Backups diarios automáticos + **PITR** (plan Pro) | RPO ≤ 5 min / RTO < 1 h |
| Storage | Versionado + réplica; export periódico a bucket frío | RPO 24 h |
| Esquema/código | Migraciones Laravel + SQL en git (fuente de verdad) | inmediato |
| Config infra | Terraform Cloudflare en git (estado remoto cifrado) | inmediato |
| Secretos | Vault/Secrets Manager con versionado y rotación | inmediato |

Restauración: `supabase db restore` (PITR) o recrear con `migrate` + `db:seed`/pgloader.
Probar el restore trimestralmente (game day).

---

## 7. Escalabilidad y costos

Sitio *read-mostly* → la palanca es el **cacheo en el borde**, no el cómputo.

| Concurrencia | Arquitectura | USD/mes aprox. |
|---|---|---|
| **100** | 1 instancia (Nginx+FPM) + Supabase Free/Pro + Cloudflare Free | $25 – 80 |
| **1.000** | + Cloudflare cache de página completa + Supabase Pro (PITR) + Redis | $100 – 250 |
| **10.000** | 2–3 instancias tras LB + réplica de lectura Supabase + Redis + WAF | $400 – 900 |
| **Crecimiento** | Autoescalado, multi-AZ; CDN sirve el grueso desde el edge | escala sublineal |

A 10k concurrentes el origen apenas recibe tráfico si el HTML va cacheado en Cloudflare.

---

## 8. Comparativa antes / después

| Dimensión | Antes | Después |
|---|---|---|
| TLS | Ninguno (HTTP) | Full(Strict) TLS 1.3 + HSTS |
| CDN/WAF/DDoS | No | Cloudflare global |
| Headers seguridad | Ninguno | CSP + 5 headers (middleware + borde) |
| Debug en prod | `APP_DEBUG=true` | `false` + Sentry |
| BD | MySQL local, sin réplica | Supabase Postgres + PITR + RLS + réplica |
| Búsqueda | `LIKE %x%` full-scan | `ILIKE` con GIN pg_trgm |
| Índices | Solo PK/unique | Compuestos por filtro real |
| Storage | Disco local | Supabase Storage (S3) con policies |
| Secretos | En compose versionado | Vault/Secrets + rotación |
| Observabilidad | Logs locales | Sentry (errores+perf+replay+releases) |
| CI/CD | Manual | GitHub Actions: build/test/SAST/scan/deploy/rollback |
| Escala | 1 nodo | CDN edge + LB + autoescalado |

---

## 9. Roadmap de ejecución

1. **P0 (días):** middleware headers (✅ hecho), `.env` prod endurecido, TLS+HSTS, secretos fuera de compose, cerrar 3306.
2. **P1 (sem 1):** Supabase Postgres (migrate+datos), índices (✅ migración creada), RLS+Storage, Cloudflare proxy+WAF+cache.
3. **P2 (sem 2):** Sentry full, CI/CD con SAST/Trivy/coverage.
4. **P3 (sem 3+):** cache full-page, Redis, WebP/AVIF, self-host fuentes; LB + réplica al crecer.
