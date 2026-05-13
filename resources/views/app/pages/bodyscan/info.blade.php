@extends('layouts.modern')

@section('page-title', 'Body Scan Info')

@php
    $activeNav = 'body';
@endphp

@section('content')
    <main class="modern-main-content">
        <section class="mb-4">
            <h3 class="eyebrow mb-2">Introduction</h3>
            <h1 class="hero-heading mb-0">
                Biomagnetism <span class="italic-wellness">Body Scan</span>
            </h1>
        </section>

        <div class="modern-info-card module-intro-card mb-4">
            <p class="cs-card-header mb-3">3D Body Scan / Escaneo Corporal 3D</p>
            <div class="icon-tile icon-tile-body-scan mb-3">
                <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
            <span class="pill pill-body-scan mb-3"><span class="pill-dot"></span>Body Scan</span>
            <h5 class="fw-bold text-dark mb-2">Full Body Analysis</h5>
            <p class="text-secondary mb-1">High-definition 3D male and female models featuring over 800 anatomical pairs for precise magnet placement.</p>
            <p class="cs-desc-es mb-0">Modelos 3D de alta definición (masculino y femenino) con más de 800 pares anatómicos para una colocación precisa de los imanes.</p>
        </div>

        <div class="row modern-row-gap">
            <div class="col-12 col-lg-4">
                @include('app.pages.bodyscan.partials.info_menu')
            </div>
            <div class="col-12 col-lg-8">
                @include('app.pages.bodyscan.partials.info_main')
            </div>
        </div>
    </main>
@endsection
