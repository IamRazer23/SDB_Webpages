<?php

if (! function_exists('media_url')) {
    /**
     * Resuelve la URL pública de un archivo de medios (imágenes, PDFs) cuya
     * ruta se guarda en la base de datos (p. ej. Product::image_path,
     * Catalog::file_path).
     *
     * Convención (Opción A): los archivos viven en `public/media/` y la base
     * de datos guarda solo la ruta RELATIVA a esa carpeta, por ejemplo
     * "productos/standard-m10.webp" → se sirve en "/media/productos/standard-m10.webp".
     *
     * Escape hatch: si la base de datos guarda una URL absoluta (http/https o
     * protocolo-relativa //), se devuelve tal cual. Así una imagen puntual
     * puede vivir en un CDN o en Supabase Storage sin cambiar el código.
     *
     * @param  string|null  $path      Ruta relativa en public/media o URL absoluta.
     * @param  string|null  $fallback  URL a devolver cuando $path está vacío.
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        $path = $path !== null ? trim($path) : '';

        if ($path === '') {
            return $fallback;
        }

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '//')
        ) {
            return $path;
        }

        return asset('media/' . ltrim($path, '/'));
    }
}
