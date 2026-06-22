@extends('layouts.app')

@section('title', $product->name . ' — SD Biosensor Panamá')

@section('content')

{{-- Banner --}}
<section class="page-banner products-banner">
    <div class="container">
        <h1>{{ $product->name }}</h1>
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span>/</span>
            <a href="{{ route('products.index') }}">Productos</a>
            <span>/</span>
            <a href="{{ route('products.index', ['categoria' => $product->category->slug]) }}">
                {{ $product->category->name }}
            </a>
            <span>/</span>
            <span>{{ $product->name }}</span>
        </nav>
    </div>
</section>

{{-- Detalle del producto --}}
<section class="section">
    <div class="container product-detail-grid">

        {{-- Imagen --}}
        <div class="product-detail-image">
            @if($product->image_path)
                <img src="{{ media_url($product->image_path) }}" alt="{{ $product->name }}">
            @else
                <div class="img-placeholder img-placeholder--large">
                    <i class="fas fa-flask fa-4x"></i>
                    <p>Imagen no disponible</p>
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="product-detail-info">
            <span class="product-cat-tag"
                  style="background: {{ $product->category->color }}">
                {{ $product->category->name }}
            </span>
            <h1>{{ $product->name }}</h1>

            @if($product->description)
                <p class="product-desc">{{ $product->description }}</p>
            @endif

            {{-- Certificaciones --}}
            @if($product->certifications)
            <div class="cert-section">
                <h3>Certificaciones</h3>
                <div class="cert-badges cert-badges--large">
                    @foreach($product->certifications as $cert)
                    <span class="badge cert-{{ strtolower(str_replace(['_',' '],'-',$cert)) }}">
                        {{ match($cert) {
                            'CE'     => 'CE Marcado',
                            'KMFDS'  => 'KMFDS Aprobado',
                            'WHO_PQ' => 'OMS PQ Aprobado',
                            'TGA'    => 'TGA Aprobado',
                            'FDA'    => 'FDA Autorizado',
                            'IVDD'   => 'IVDD',
                            default  => $cert,
                        } }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="product-actions">
                <a href="{{ route('support.index') }}" class="btn-primary">
                    <i class="fas fa-download"></i> Descargar IFU
                </a>
                <a href="mailto:panama@sdbiosensor.com?subject=Consulta: {{ $product->name }}"
                   class="btn-outline">
                    <i class="fas fa-envelope"></i> Solicitar Información
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Productos relacionados --}}
@if($related->isNotEmpty())
<section class="section" style="background: var(--color-bg-light)">
    <div class="container">
        <h2 class="section-title">Productos Relacionados</h2>
        <div class="products-grid">
            @foreach($related as $product)
                @include('products._card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
