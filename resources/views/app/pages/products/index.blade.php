@extends('layouts.modern')

@section('page-title', 'More')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@section('content')
    <main class="modern-main-content">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('More') }}</h1>
                    <p class="modern-page-subtitle">Más</p>
                </div>
            </header>

            <section class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel" style="padding: 42px 30px;">
                    <p style="font-size: 1.15rem; margin-bottom: 10px;">
                        This section is being updated.
                    </p>
                    <p style="color: #ff5a47; font-size: 1.15rem; margin-bottom: 0;">
                        Esta sección se está actualizando.
                    </p>
                </div>
            </section>
        </div>
    </main>
@endsection
