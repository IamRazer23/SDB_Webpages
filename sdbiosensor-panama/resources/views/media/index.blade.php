@extends('layouts.app')

@section('title', 'Centro de Medios — SD Biosensor Panamá')

@section('content')

<section class="page-banner" style="background: linear-gradient(135deg, #1a3a6e 0%, #0a1f3f 100%)">
    <div class="container">
        <h1>Centro de Medios</h1>
        <p>Noticias, comunicados y catálogos de SD Biosensor Panamá.</p>
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span>/</span>
            <span>Centro de Medios</span>
        </nav>
    </div>
</section>

{{-- Sub-navegación --}}
<div class="sub-tabs-bar">
    <div class="container">
        <div class="sub-tabs">
            <a href="#noticias" class="sub-tab active">Noticias</a>
            <a href="#catalogos" class="sub-tab">Catálogos</a>
        </div>
    </div>
</div>

{{-- Sección Noticias --}}
<section class="section" id="noticias">
    <div class="container">
        <h2 class="section-title reveal">Noticias y Comunicados</h2>

        <div class="news-list">
            @forelse($news as $item)
            <article class="news-list-item reveal">
                <div class="news-list-img">
                    @if($item->image_path)
                        <img src="{{ media_url($item->image_path) }}" alt="{{ $item->title }}" loading="lazy">
                    @else
                        <div class="img-placeholder news-placeholder-sm">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    @endif
                </div>
                <div class="news-list-body">
                    <div class="news-meta">
                        <span class="news-badge">{{ $item->category->name }}</span>
                        <time>{{ $item->published_at->format('d/m/Y') }}</time>
                    </div>
                    <h3>{{ $item->title }}</h3>
                    <p>{{ Str::limit($item->summary, 150) }}</p>
                </div>
            </article>
            @empty
            <p class="no-results">No hay noticias disponibles.</p>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="pagination-wrapper">
            {{ $news->links() }}
        </div>
    </div>
</section>

{{-- Sección Catálogos --}}
<section class="section" id="catalogos" style="background: var(--color-bg-light)">
    <div class="container">
        <h2 class="section-title reveal">Catálogos de Productos</h2>
        <div class="catalogs-grid">
            @forelse($catalogs as $catalog)
            <div class="catalog-card reveal">
                <div class="catalog-cover">
                    @if($catalog->cover_image_path)
                        <img src="{{ media_url($catalog->cover_image_path) }}" alt="{{ $catalog->title }}" loading="lazy">
                    @else
                        <div class="img-placeholder catalog-placeholder">
                            <i class="fas fa-book-open fa-3x"></i>
                            <span>{{ $catalog->year }}</span>
                        </div>
                    @endif
                </div>
                <div class="catalog-info">
                    <h4>{{ $catalog->title }}</h4>
                    <span class="catalog-year">{{ $catalog->year }}</span>
                    @if($catalog->file_path)
                        <a href="{{ media_url($catalog->file_path) }}" class="btn-download-catalog" download>
                            <i class="fas fa-download"></i> Descargar PDF
                        </a>
                    @else
                        <span class="btn-download-catalog disabled">
                            <i class="fas fa-clock"></i> Próximamente
                        </span>
                    @endif
                </div>
            </div>
            @empty
            <p class="no-results">No hay catálogos disponibles.</p>
            @endforelse
        </div>
    </div>
</section>

@endsection
