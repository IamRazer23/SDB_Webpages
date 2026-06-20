# Despliegue estático en Cloudflare Pages (versionado por branch)

El sitio se **genera estático** desde Laravel (leyendo Supabase) y se publica en
**Cloudflare Pages**. Cada branch de GitHub obtiene su propia URL; `main` es producción.

## Cómo funciona el versionado por branch
- Push a `main` → **producción** (tu dominio).
- Push a cualquier otro branch (ej. `rediseno`) → **preview** en
  `https://rediseno.sdbiosensor-panama.pages.dev`.
- Así pruebas versiones distintas sin afectar producción.

## Generar el sitio en local (para revisar antes de subir)
```bash
docker compose exec app php artisan export
# Resultado en la carpeta dist/. Para verlo:
npx serve dist      # o cualquier servidor estático
```

## Configuración única (una sola vez)

### 1. Crear el proyecto en Cloudflare Pages
Dashboard → **Workers & Pages → Create → Pages → Direct Upload** →
nombre del proyecto: **`sdbiosensor-panama`** (debe coincidir con el del workflow).

### 2. Secrets y variables en GitHub
Repo → **Settings → Secrets and variables → Actions**:

**Secrets:**
| Nombre | Valor |
|---|---|
| `CLOUDFLARE_API_TOKEN` | Token con permiso *Cloudflare Pages: Edit* |
| `CLOUDFLARE_ACCOUNT_ID` | ID de tu cuenta Cloudflare |
| `APP_KEY` | La APP_KEY de Laravel (`php artisan key:generate --show`) |
| `DB_HOST` | `aws-1-us-east-1.pooler.supabase.com` |
| `DB_USERNAME` | `postgres.lufifpwuhexzmfdxmdyb` |
| `DB_PASSWORD` | (tu contraseña de Supabase) |

**Variables:**
| Nombre | Valor |
|---|---|
| `SITE_URL` | `https://www.sdbiosensor.com.pa` (o el dominio final) |

### 3. Listo
Cada `git push` dispara [.github/workflows/deploy-static.yml](../.github/workflows/deploy-static.yml):
genera el estático y lo publica en Pages bajo el branch correspondiente.

## Dominio propio
En el proyecto de Pages → **Custom domains** → agrega tu dominio. Como el DNS ya
está en Cloudflare, se configura solo.

## ⚠️ Limitación conocida (importante)
Un sitio estático **no ejecuta PHP**, así que el filtrado por **pestañas de categoría**
(`/productos?categoria=...`) y el **buscador** dejan de funcionar del lado del servidor.

Opciones:
- **A)** Convertir ese filtrado a **JavaScript en el navegador** (filtra la lista ya
  renderizada). Es la solución correcta para estático; requiere un ajuste en las
  vistas de productos/soporte. *(Puedo implementarlo.)*
- **B)** Dejar las páginas sin filtro dinámico (mostrar todo).

El resto del sitio (home, detalle de cada producto, secciones) funciona perfecto
en estático.

## Actualizar contenido
Como no hay BD en vivo, cuando cambies contenido en Supabase debes **regenerar**:
vuelve a correr el workflow (push, o *Run workflow* manual en la pestaña Actions).
