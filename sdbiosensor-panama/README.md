# SD Biosensor Panamá — Sitio Web Corporativo

Sitio web de **SD Biosensor Panamá** (subsidiaria de SD Biosensor, Inc., operando bajo **Mirero Corp**), construido con **Laravel 11** y arquitectura **MVC estricta**. Los productos, noticias, catálogos y descargas se extraen dinámicamente desde **MySQL** mediante **Eloquent ORM**.

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Framework | Laravel 11 (PHP 8.2+) |
| Plantillas | Blade |
| Base de datos | MySQL 8.x |
| ORM | Eloquent |
| Assets | Vite + CSS vanilla |
| Íconos | Font Awesome 6 Free (CDN) |
| Fuentes | Google Fonts — Noto Sans |
| JS | Vanilla JS |

## Requisitos previos

- PHP **8.3+** con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo` (Laravel 13)
- **Composer** 2.x
- **Node.js** 18+ y npm
- **MySQL** 8.x

## Instalación con Docker (recomendado — sin instalar PHP/MySQL localmente)

Requiere únicamente **Docker Desktop** (o Docker Engine + Compose v2).

```bash
cd sdbiosensor-panama

# Levanta todo: PHP-FPM + Nginx + MySQL + build de assets
docker compose up -d --build
```

El contenedor de aplicación se encarga automáticamente de: instalar dependencias de Composer, crear el `.env`, generar `APP_KEY`, esperar a MySQL, ejecutar migraciones y seeders, y crear el enlace de `storage`.

Una vez que los servicios estén arriba, el sitio queda disponible en **http://localhost:8000**.

| Servicio | Descripción | Puerto host |
|---|---|---|
| `web` | Nginx (sirve la app) | `8000` |
| `app` | PHP-FPM 8.3 (Laravel) | — |
| `db` | MySQL 8 | `3306` |
| `node` | Compila los assets con Vite (corre una vez y termina) | — |

> El servicio `node` compila los assets y finaliza; es normal verlo como *Exited (0)*. Si modificas CSS/JS, recompila con:
> `docker compose run --rm node npm run build`

Comandos útiles de Artisan dentro del contenedor:

```bash
docker compose exec app php artisan migrate:fresh --seed   # reiniciar BD
docker compose exec app php artisan tinker
docker compose exec app php artisan route:list
docker compose down            # detener
docker compose down -v         # detener y borrar el volumen de MySQL
```

> En el primerísimo arranque, si abres el sitio antes de que `node` termine de compilar, Vite puede mostrar "manifest not found"; basta refrescar tras unos segundos.

---

## Instalación manual (PHP + MySQL locales)

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Configurar entorno
cp .env.example .env
php artisan key:generate

# 3. Crear la base de datos en MySQL
#    CREATE DATABASE sdbiosensor_panama CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
#    Ajustar credenciales en .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4. Migrar y poblar con datos de ejemplo
php artisan migrate
php artisan db:seed

# 5. Compilar assets front-end
npm install && npm run build

# 6. Enlace simbólico de storage (para imágenes subidas)
php artisan storage:link

# 7. Levantar el servidor de desarrollo
php artisan serve
```

El sitio quedará disponible en **http://localhost:8000**.

> Para desarrollo con recarga en caliente de assets, ejecutar `npm run dev` en una terminal aparte mientras corre `php artisan serve`.

## Configuración de la base de datos (`.env`)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sdbiosensor_panama
DB_USERNAME=root
DB_PASSWORD=
```

## Estructura de rutas

| Ruta | Nombre | Controlador |
|---|---|---|
| `/` | `home` | `HomeController@index` |
| `/productos` | `products.index` | `ProductController@index` |
| `/productos/{slug}` | `products.show` | `ProductController@show` |
| `/investigacion` | `research.index` | `ResearchController@index` |
| `/centro-de-medios` | `media.index` | `MediaController@index` |
| `/soporte` | `support.index` | `SupportController@index` |

## Modelo de datos

- **product_categories** → **products** (1:N) — líneas STANDARD Q/F/E/M/i/C, BGMS, Etc.
- **news_categories** → **news_items** (1:N) — avisos, noticias, eventos
- **catalogs** — catálogos PDF por año
- **downloads** — folletos, IFU, manuales, software

### Filtrado dinámico

- **Productos:** pestañas por categoría (`?categoria=standard-q`) y buscador (`?buscar=...`)
- **Soporte:** filtro por tipo (`?categoria=software`) y buscador (`?buscar=...`)

## Notas de contenido

- **Nombres de producto y certificaciones NO se traducen** (`COVID-19 Ag Home Test`, `STANDARD Q`, `BGMS`, `CE`, `KMFDS`, `WHO PQ`, `TGA`). Los labels en español aparecen como complemento (ej. "KMFDS Aprobado").
- Las imágenes (`image_path`, `cover_image_path`, `file_path`) están en `null` en los seeders. Las vistas muestran placeholders de Font Awesome hasta que se suban los archivos reales a `storage/app/public` (accesibles vía `storage:link`).

## Personalización visual

Los tokens de diseño (colores corporativos, colores por línea de producto, tipografía) están centralizados en las variables CSS de [resources/css/app.css](resources/css/app.css), sección `:root`.

---

© SD Biosensor, Inc. — Operado por Mirero Corp en la República de Panamá.
