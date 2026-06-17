@extends('layouts.app')

@section('title', 'Productos — SD Biosensor Panamá')

@section('content')

{{-- Banner de página --}}
<section class="page-banner products-banner">
    <div class="container">
        <h1>PRODUCTOS</h1>
        <p>SD BIOSENSOR lidera la salud humana a través de investigación y desarrollo continuos.</p>
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span>/</span>
            <span>Productos</span>
        </nav>
    </div>
</section>

{{-- Buscador de productos --}}
<div class="container">
    <form method="GET" action="{{ route('products.index') }}" class="product-search-bar">
        <input type="text" name="buscar" value="{{ $searchTerm }}"
               placeholder="Buscar por enfermedad, nombre o categoría...">
        <button type="submit"><i class="fas fa-search"></i> Buscar</button>
        @if($searchTerm)
            <a href="{{ route('products.index') }}" class="clear-search">
                <i class="fas fa-times"></i> Limpiar
            </a>
        @endif
    </form>
</div>

{{-- Productos Destacados (siempre visibles arriba) --}}
@if(!$searchTerm && $featuredProducts->isNotEmpty())
<section class="section" style="background: var(--color-bg-light); padding-top: 2rem;">
    <div class="container">
        <h2 class="section-title">Productos Destacados</h2>
        <div class="products-grid">
            @foreach($featuredProducts as $product)
                @include('products._card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Pestañas de categoría --}}
<div class="container">
    <div class="category-tabs" id="category-tabs">
        @foreach($categories as $cat)
        <a href="{{ route('products.index', ['categoria' => $cat->slug]) }}"
           class="tab {{ $activeCategory->slug === $cat->slug ? 'active' : '' }}"
           style="{{ $activeCategory->slug === $cat->slug ? 'border-color: '.$activeCategory->color.'; color: '.$activeCategory->color : '' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>
</div>

{{-- Banner de la categoría activa --}}
@if(!$searchTerm)
<div class="active-cat-banner" style="border-top: 4px solid {{ $activeCategory->color }}">
    <div class="container">
        <h2 style="color: {{ $activeCategory->color }}">{{ $activeCategory->name }}</h2>
        <p>{{ $activeCategory->long_desc ?? $activeCategory->short_desc }}</p>
    </div>
</div>
@endif

{{-- Grid de productos --}}
<section class="section products-section">
    <div class="container">
        @if($searchTerm)
            <h2 class="section-title">Resultados para: "<em>{{ $searchTerm }}</em>"</h2>
        @else
            <h2 class="section-title">Todos los Productos — {{ $activeCategory->name }}</h2>
        @endif

        <div class="products-grid">
            @forelse($products as $product)
                @include('products._card', ['product' => $product])
            @empty
                <div class="no-results">
                    <i class="fas fa-search fa-3x"></i>
                    <p>No se encontraron productos{{ $searchTerm ? " para \"$searchTerm\"" : ' en esta categoría' }}.</p>
                    <a href="{{ route('products.index') }}" class="btn-primary">Ver todos los productos</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
