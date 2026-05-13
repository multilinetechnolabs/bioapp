@extends('layouts.modern')

@section('page-title', 'Chakra Scan Info')

@php
    $activeNav = 'chakra';
@endphp

@section('content')
    <main class="modern-main-content">
        <section class="mb-4">
            <h3 class="eyebrow mb-2">Introduction</h3>
            <h1 class="hero-heading mb-0">
                Chakra <span class="italic-wellness">Body Scan</span>
            </h1>
        </section>

        <div class="modern-info-card module-intro-card mb-4">
            <p class="cs-card-header mb-3">Proprietary Chakra Method / Método de Chakra Propietario</p>
            <div class="icon-tile icon-tile-chakra-scan mb-3">
                <svg class="module-card-icon" fill="none" stroke="currentColor" viewBox="0 0 100 100" aria-hidden="true" stroke-width="1.2">
                    <circle cx="50" cy="50" r="32"/><circle cx="50" cy="50" r="16"/><circle cx="66" cy="50" r="16"/><circle cx="58" cy="36.1" r="16"/><circle cx="42" cy="36.1" r="16"/><circle cx="34" cy="50" r="16"/><circle cx="42" cy="63.9" r="16"/><circle cx="58" cy="63.9" r="16"/>
                </svg>
            </div>
            <span class="pill pill-chakra-scan mb-3"><span class="pill-dot"></span>Chakra Scan</span>
            <h5 class="fw-bold text-dark mb-2">Energy Center Map</h5>
            <p class="text-secondary mb-1">A specialized system developed to decode emotional blocks through a comprehensive network of Major, Minor, and Micro chakras.</p>
            <p class="cs-desc-es mb-0">Un sistema especializado desarrollado para descodificar bloqueos emocionales a través de una red integral de chakras Mayores, Menores y Micro.</p>
        </div>

        <div class="row modern-row-gap">
            <div class="col-12 col-lg-4">
                @include('app.pages.chakrascan.partials.info_menu')
            </div>
            <div class="col-12 col-lg-8">
                @include('app.pages.chakrascan.partials.info_main')
            </div>
        </div>
    </main>
@endsection
