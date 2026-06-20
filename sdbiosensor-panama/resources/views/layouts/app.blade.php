<!DOCTYPE html>
<html lang="es" class="@yield('html_class')">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SD Biosensor Panamá — Diagnóstico rápido de alta calidad operado por Mirero Corp.">
    <title>@yield('title', 'SD Biosensor Panamá')</title>

    {{-- Google Fonts: Noto Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome 6 Free --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plh7eecb2t3vMvTZ8G+w3xIPsqzBg=="
          crossorigin="anonymous" referrerpolicy="no-referrer">

    @vite('resources/css/app.css')

    @stack('head')
</head>
<body>

    @include('partials.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Botón volver arriba --}}
    <button id="scroll-top" class="scroll-top-btn" aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </button>

    {{-- Menú flotante FAB --}}
    <div class="fab-wrapper">
        <div class="fab-menu" id="fab-menu">
            <a href="{{ route('support.index') }}" class="fab-item" title="Centro de Soporte">
                <i class="fas fa-headset"></i>
            </a>
            <a href="{{ route('products.index') }}" class="fab-item" title="Productos">
                <i class="fas fa-flask"></i>
            </a>
            <a href="mailto:admin@sdbiosensor.com.pa" class="fab-item" title="Contáctenos">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
        <button class="fab-btn" id="fab-btn" aria-label="Acciones rápidas">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    @vite('resources/js/app.js')

    @stack('scripts')
</body>
</html>
