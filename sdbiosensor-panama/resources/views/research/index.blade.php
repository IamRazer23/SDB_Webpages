@extends('layouts.app')

@section('title', 'Investigación y Desarrollo — SD Biosensor Panamá')

@section('content')

<section class="page-banner" style="background: linear-gradient(135deg, #003366 0%, #001a3a 100%)">
    <div class="container">
        <h1>Investigación y Desarrollo</h1>
        <p>Innovación continua al servicio del diagnóstico médico global.</p>
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <span>/</span>
            <span>I+D</span>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="rd-intro reveal">
            <h2 class="section-title">Nuestra Filosofía de Innovación</h2>
            <p>
                SD Biosensor invierte en investigación y desarrollo para crear soluciones de diagnóstico
                rápido con la más alta sensibilidad y especificidad. Nuestros equipos científicos trabajan
                continuamente para mejorar la precisión, la velocidad y la accesibilidad de nuestras pruebas.
            </p>
        </div>

        <div class="rd-pillars">
            <div class="rd-card reveal">
                <div class="rd-icon"><i class="fas fa-dna"></i></div>
                <h3>Desarrollo de Materias Primas</h3>
                <p>Controlamos la calidad desde la selección de antígenos y anticuerpos hasta la fabricación del producto final.</p>
            </div>
            <div class="rd-card reveal">
                <div class="rd-icon"><i class="fas fa-microscope"></i></div>
                <h3>Validación Clínica</h3>
                <p>Todos nuestros productos son validados en estudios clínicos multicéntricos antes de su comercialización.</p>
            </div>
            <div class="rd-card reveal">
                <div class="rd-icon"><i class="fas fa-globe"></i></div>
                <h3>Aprobaciones Regulatorias</h3>
                <p>Nuestros productos cuentan con aprobaciones CE, KMFDS, OMS PQ, TGA y FDA para garantizar su seguridad global.</p>
            </div>
            <div class="rd-card reveal">
                <div class="rd-icon"><i class="fas fa-industry"></i></div>
                <h3>Fabricación GMP</h3>
                <p>Producción bajo estándares internacionales de Buenas Prácticas de Manufactura en nuestras instalaciones de Corea del Sur.</p>
            </div>
        </div>
    </div>
</section>

@endsection
