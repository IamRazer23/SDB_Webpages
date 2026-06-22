@extends('layouts.app')

@section('title', 'SD Biosensor Panamá — Diagnóstico Rápido de Alta Calidad')

{{-- Activa el scroll-snap por sección (solo en el home) --}}
@section('html_class', 'snap-home')

@section('content')

{{-- ============================================================
     SECCIÓN HERO — Carrusel
     ============================================================ --}}
<section class="hero" id="hero">
    <div class="hero-slides" id="hero-slides">

        {{-- Slide 1: STANDARD F --}}
        <a class="hero-slide hero-banner active"
           style="background-image: url('/images/hero/standard-f.png')"
           href="{{ route('products.index') }}"
           aria-label="STANDARD F — Sistema de Inmunoensayo Fluorescente"></a>

        {{-- Slide 2: POC in-vitro (planta SD Biosensor) --}}
        <a class="hero-slide hero-banner"
           style="background-image: url('/images/hero/poc-in-vitro.png')"
           href="{{ route('research.index') }}"
           aria-label="SD Biosensor — POC in-vitro Total Platform Company"></a>

        {{-- Slide 3: STANDARD M10 --}}
        <a class="hero-slide hero-banner"
           style="background-image: url('/images/hero/standard-m10.jpg')"
           href="{{ route('products.index') }}"
           aria-label="STANDARD M10 — Plataforma MDx Versátil Point-of-Care"></a>
    </div>

    {{-- Controles del carrusel --}}
    <button class="hero-arrow hero-prev" id="hero-prev" aria-label="Anterior">&#8249;</button>
    <button class="hero-arrow hero-next" id="hero-next" aria-label="Siguiente">&#8250;</button>

    {{-- Indicadores --}}
    <div class="hero-dots" id="hero-dots">
        <button class="hero-dot active" data-slide="0" aria-label="Slide 1"></button>
        <button class="hero-dot" data-slide="1" aria-label="Slide 2"></button>
        <button class="hero-dot" data-slide="2" aria-label="Slide 3"></button>
    </div>
</section>

{{-- ============================================================
     SECCIÓN NOTICIAS RECIENTES
     ============================================================ --}}
<section class="section news-section">
    <div class="container">
        <h2 class="section-title reveal">Últimas Noticias</h2>
        <div class="news-grid-cards">
            @foreach($recentNews as $item)
            <div class="news-card reveal">
                <div class="news-card-img">
                    @if($item->image_path)
                        <img src="{{ media_url($item->image_path) }}" alt="{{ $item->title }}" loading="lazy">
                    @else
                        <div class="img-placeholder news-placeholder">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    @endif
                </div>
                <div class="news-card-body">
                    <span class="news-badge">{{ $item->category->name }}</span>
                    <span class="news-date">{{ $item->published_at->format('Y.m.d') }}</span>
                    <h4>{{ $item->title }}</h4>
                    <p>{{ Str::limit($item->summary, 100) }}</p>
                    <a href="{{ route('media.index') }}" class="news-more">Leer más →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     SECCIÓN NOTICIA DESTACADA
     ============================================================ --}}
@if($featuredNews)
<section class="section featured-news-section">
    <div class="container featured-news-grid">
        <div class="featured-news-image reveal">
            @if($featuredNews->image_path)
                <img src="{{ media_url($featuredNews->image_path) }}" alt="{{ $featuredNews->title }}">
            @else
                <div class="img-placeholder featured-placeholder">
                    <i class="fas fa-star"></i>
                    <p>Noticia Destacada</p>
                </div>
            @endif
        </div>
        <div class="featured-news-content reveal">
            <span class="news-badge badge-featured">{{ $featuredNews->category->name }}</span>
            <span class="news-date">{{ $featuredNews->published_at->format('Y.m.d') }}</span>
            <h3>{{ $featuredNews->title }}</h3>
            <p>{{ $featuredNews->summary }}</p>
            <a href="{{ route('media.index') }}" class="btn-more">MÁS →</a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     SECCIÓN LÍNEAS DE PRODUCTO
     ============================================================ --}}
<section class="section product-lines-section">
    <div class="container">
        <h2 class="section-title reveal">PRODUCTOS</h2>
        <p class="section-subtitle reveal">Soluciones de diagnóstico rápido para cada necesidad clínica.</p>
        <div class="categories-grid reveal">
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['categoria' => $cat->slug]) }}"
               class="category-btn"
               style="background-color: {{ $cat->color }}">
                {{ $cat->name }}
                <i class="fas fa-chevron-right"></i>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     SECCIÓN PRODUCTOS DESTACADOS
     ============================================================ --}}
<section class="section featured-products-section" style="background: var(--color-bg-light)">
    <div class="container">
        <h2 class="section-title reveal">Productos Destacados</h2>
        <div class="products-grid">
            @foreach($featuredProducts as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="product-card reveal">
                <div class="cert-badges">
                    @foreach($product->certifications ?? [] as $cert)
                        <span class="badge cert-{{ strtolower(str_replace(['_', ' '], '-', $cert)) }}">
                            {{ match($cert) {
                                'CE'     => 'CE',
                                'KMFDS'  => 'KMFDS',
                                'WHO_PQ' => 'OMS PQ',
                                'TGA'    => 'TGA',
                                'FDA'    => 'FDA',
                                'IVDD'   => 'IVDD',
                                default  => $cert,
                            } }}
                        </span>
                    @endforeach
                </div>
                <div class="product-image">
                    @if($product->image_path)
                        <img src="{{ media_url($product->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <div class="img-placeholder">
                            <i class="fas fa-vial fa-2x"></i>
                        </div>
                    @endif
                </div>
                <p class="product-category">{{ $product->category->name }}</p>
                <h4 class="product-name">{{ $product->name }}</h4>
            </a>
            @endforeach
        </div>
        <div class="section-cta">
            <a href="{{ route('products.index') }}" class="btn-primary">
                Ver Todos los Productos <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

{{-- ============================================================
     SECCIÓN SOBRE NOSOTROS / EMPRESA
     ============================================================ --}}
<section class="section about-section">
    <div class="container about-grid">
        <div class="about-content reveal">
            <h2>SD Biosensor Panamá</h2>
            <p class="about-lead">
                Subsidiaria de SD Biosensor, Inc. (República de Corea), operando bajo
                <strong>Mirero Corp</strong> en la República de Panamá.
            </p>
            <p>
                Nos especializamos en la distribución y soporte técnico de kits de diagnóstico
                rápido de alta sensibilidad y especificidad, utilizados por hospitales,
                laboratorios clínicos y programas de salud pública en toda la región.
            </p>
            <ul class="about-stats">
                <li>
                    <span class="stat-number">+100</span>
                    <span class="stat-label">Países</span>
                </li>
                <li>
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Productos</span>
                </li>
                <li>
                    <span class="stat-number">OMS</span>
                    <span class="stat-label">PQ Aprobado</span>
                </li>
            </ul>
        </div>
        <div class="about-visual reveal">
            <div class="about-placeholder">
                <i class="fas fa-dna"></i>
            </div>
        </div>
    </div>
</section>

@endsection
