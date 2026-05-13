@extends('layouts.modern')

@section('page-title', 'Data Cache Help')

@php($activeNav = 'data')

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/data_cache.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content">
        <header class="modern-page-header">
            <div>
                <h1 class="modern-page-title">Data Cache Help</h1>
                <p class="modern-page-subtitle">Caché de datos &mdash; Ayuda</p>
            </div>
            <a href="{{ route('app.data_cache') }}" class="modern-btn modern-btn--outline">
                <span aria-hidden="true">&larr;</span> Back to Data Cache / Volver
            </a>
        </header>

        <section class="modern-info-card">
            @include('app.pages.data_cache.partials.help_content')
        </section>
    </main>
@endsection
