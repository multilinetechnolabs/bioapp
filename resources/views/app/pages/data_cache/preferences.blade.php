@extends('layouts.modern')

@section('page-title', 'Data Cache Preferences')

@php
    $activeNav = 'data';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/data_cache.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content">
        <header class="modern-page-header">
            <div>
                <h1 class="modern-page-title">Preferences</h1>
                <p class="modern-page-subtitle">Preferencias</p>
            </div>
            <a href="{{ route('app.data_cache') }}" class="modern-btn modern-btn--outline">
                <span aria-hidden="true">&larr;</span> Back to Data Cache / Volver
            </a>
        </header>

        <section class="modern-info-card">
            <p class="mb-2">
                Update your practice details, billing information, and logos from this page. / Actualiza los detalles de tu práctica, información de facturación y logos desde esta página.
            </p>
            <p class="text-muted mb-4">
                Actualice desde esta página los datos de su consulta, la información de facturación y los logotipos.
            </p>
            @include('app.pages.data_cache.partials.preferences_content')
        </section>
    </main>
@endsection

@push('scripts')
    @include('app.pages.data_cache.modals.js.preferences')
@endpush
