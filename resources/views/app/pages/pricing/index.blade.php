@extends('layouts.modern')

@section('page-title', 'Plans & Pricing')

@php
    $activeNav = 'home';
    $useAppShell = true;
    $pl = $locale ?? 'en';
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ $pl === 'es' ? 'Planes y Precios' : ($pl === 'fr' ? 'Plans et Tarifs' : 'Plans &amp; Pricing') }}</h1>
                    <p class="modern-page-subtitle">{{ $pl === 'es' ? 'Elige el plan que mejor se adapta a ti' : ($pl === 'fr' ? 'Choisissez le plan qui vous convient' : 'Choose the plan that works for you') }}</p>
                </div>
            </header>

            <section style="padding: 0 1.5rem;">
                @include('partials.modern.pricing')
            </section>
        </div>
    </main>
@endsection
