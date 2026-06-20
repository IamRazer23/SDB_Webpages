<?php

/**
 * Configuración de Sentry (error tracking + performance + release tracking).
 *
 * Requiere instalar el paquete (no incluido en vendor todavía):
 *     composer require sentry/sentry-laravel
 *
 * Y registrar la captura de excepciones en bootstrap/app.php:
 *     ->withExceptions(function (Exceptions $exceptions) {
 *         \Sentry\Laravel\Integration::handles($exceptions);
 *     })
 *
 * Variables en .env(.production):
 *     SENTRY_LARAVEL_DSN, SENTRY_TRACES_SAMPLE_RATE, SENTRY_RELEASE, SENTRY_ENVIRONMENT
 */
return [

    'dsn' => env('SENTRY_LARAVEL_DSN'),

    // Versión desplegada → habilita Release Tracking y "suspect commits".
    'release' => env('SENTRY_RELEASE'),

    'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV', 'production')),

    // Performance Monitoring: muestreo de transacciones (10% en prod).
    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.1),

    // No enviar datos personales por defecto (privacidad/fuga de datos).
    'send_default_pii' => false,

    'breadcrumbs' => [
        'sql_queries'  => true,    // detecta consultas lentas / N+1
        'sql_bindings' => false,   // nunca enviar valores enlazados (PII)
        'cache'        => true,
        'http_client_requests' => true,
    ],

    'tracing' => [
        'queue_job_transactions' => true,
        'sql_queries'            => true,
        'views'                  => true,
        'default_integrations'   => true,
    ],

    // No reportar errores en desarrollo local.
    'in_app_exclude' => [base_path('vendor')],
];
