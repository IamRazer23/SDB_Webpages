@extends('layouts.app')

@section('title', 'Centro de Soporte — SD Biosensor Panamá')

@section('content')

<section class="page-banner" style="background: linear-gradient(135deg, #222244 0%, #0f0f22 100%)">
    <div class="container">
        <h1>Centro de Soporte</h1>
        <p>Documentación técnica, software y recursos de soporte.</p>
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span>/</span>
            <span>Centro de Soporte</span>
        </nav>
    </div>
</section>

{{-- Sub-pestañas --}}
<div class="sub-tabs-bar">
    <div class="container">
        <div class="sub-tabs">
            <a href="#" class="sub-tab">Reporte de Conducta Inapropiada</a>
            <a href="#" class="sub-tab">Soporte Técnico</a>
            <a href="{{ route('support.index') }}" class="sub-tab active">Descargas</a>
            <a href="mailto:panama@sdbiosensor.com" class="sub-tab">Contáctenos</a>
        </div>
    </div>
</div>

{{-- Sección Descargas --}}
<section class="section">
    <div class="container">
        <h2 class="section-title reveal">Descargas</h2>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('support.index') }}" class="download-filters reveal">
            <div class="filter-radios">
                @foreach(['todos' => 'Todos', 'folleto' => 'Folleto', 'ifu' => 'IFU', 'manual' => 'Manual', 'software' => 'S/W', 'etc' => 'Etc.'] as $val => $label)
                <label class="radio-label {{ $category === $val ? 'active' : '' }}">
                    <input type="radio" name="categoria" value="{{ $val }}"
                           {{ $category === $val ? 'checked' : '' }}
                           onchange="this.form.submit()">
                    {{ $label }}
                </label>
                @endforeach
            </div>
            <div class="filter-search">
                <input type="text" name="buscar" value="{{ $search }}"
                       placeholder="Buscar descarga...">
                <button type="submit"><i class="fas fa-search"></i></button>
                @if($search)
                    <a href="{{ route('support.index', ['categoria' => $category]) }}" class="clear-search">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabla de descargas --}}
        <div class="table-wrapper reveal">
            <table class="downloads-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Categoría</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Descargar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($downloads as $download)
                    <tr>
                        <td class="td-num">{{ str_pad($loop->index + 1, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <span class="category-tag">
                                {{ strtoupper($download->category === 'software' ? 'S/W' : $download->category) }}
                            </span>
                        </td>
                        <td class="td-title">{{ $download->title }}</td>
                        <td class="td-date">
                            {{ \Carbon\Carbon::parse($download->published_at)->format('Y.m.d') }}
                        </td>
                        <td class="td-action">
                            @if($download->file_path)
                                <a href="{{ Storage::url($download->file_path) }}"
                                   class="btn-download"
                                   download
                                   title="Descargar {{ $download->title }}">
                                    <i class="fas fa-download"></i>
                                </a>
                            @else
                                <span class="btn-download disabled" title="Archivo no disponible">
                                    <i class="fas fa-download"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="no-results">
                            <i class="fas fa-search"></i>
                            No se encontraron descargas{{ $search ? " para \"$search\"" : '' }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection
