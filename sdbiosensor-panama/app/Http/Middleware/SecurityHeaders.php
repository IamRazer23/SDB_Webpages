<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cabeceras de seguridad HTTP (OWASP A05) + CSP.
 *
 * Defensa en profundidad: Clickjacking, MIME sniffing, fuga de referrer,
 * y mitigación de XSS mediante Content-Security-Policy.
 *
 * Nota: hoy existen estilos inline (style="..."), por eso style-src incluye
 * 'unsafe-inline'. Para CSP estricta, migrar esos estilos a clases/nonces.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $csp = implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "frame-ancestors 'none'",
            "form-action 'self'",
            "object-src 'none'",
            "img-src 'self' data: https://*.supabase.co",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
            "script-src 'self'",
            "connect-src 'self' https://*.supabase.co https://*.ingest.sentry.io https://*.ingest.us.sentry.io",
            "worker-src 'self' blob:",
            "upgrade-insecure-requests",
        ]);

        $headers = [
            'Content-Security-Policy' => $csp,
            'X-Frame-Options'         => 'DENY',
            'X-Content-Type-Options'  => 'nosniff',
            'Referrer-Policy'         => 'strict-origin-when-cross-origin',
            'Permissions-Policy'      => 'geolocation=(), microphone=(), camera=(), interest-cohort=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
        ];

        // HSTS solo bajo HTTPS (evita romper el desarrollo local en http).
        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
        }

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
