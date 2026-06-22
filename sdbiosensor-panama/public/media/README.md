# Carpeta de medios de contenido (`public/media/`)

Imágenes y archivos **referenciados desde la base de datos** (productos, noticias,
catálogos, descargas). Se versionan en git y Cloudflare los sirve por su CDN en
`/media/...` (gratis, rápido, sin costo de egress de Supabase).

> Las imágenes de **marca** (logo, banners del hero) NO van aquí: viven en
> `public/images/` porque son parte del diseño, no contenido editable.

## Cómo agregar una imagen/archivo

1. Copia el archivo en la subcarpeta que corresponda, p. ej.
   `public/media/productos/standard-m10.webp`.
2. En la base de datos (Supabase), guarda la **ruta relativa a esta carpeta**
   en la columna correspondiente:

   | Tabla        | Columna             | Ejemplo de valor                  |
   |--------------|---------------------|-----------------------------------|
   | `products`   | `image_path`        | `productos/standard-m10.webp`     |
   | `news_items` | `image_path`        | `noticias/lanzamiento-m10.webp`   |
   | `catalogs`   | `cover_image_path`  | `catalogos/portada-2026.webp`     |
   | `catalogs`   | `file_path`         | `catalogos/catalogo-2026.pdf`     |
   | `downloads`  | `file_path`         | `descargas/manual-m10.pdf`        |

3. Vuelve a desplegar (push) para que el build estático regenere las páginas.
   En un sitio estático, cualquier cambio de contenido requiere reconstruir.

La resolución la hace el helper `media_url()` (ver `app/helpers.php`):
`media_url('productos/x.webp')` → `/media/productos/x.webp`.

## Notas

- Si el valor en la BD es una **URL absoluta** (`https://...`), `media_url()`
  la devuelve tal cual: útil para servir una imagen puntual desde un CDN o
  Supabase Storage sin tocar el código.
- **Optimiza los pesos**: usa WebP/JPG (~200–500 KB). Evita PNG pesados.
