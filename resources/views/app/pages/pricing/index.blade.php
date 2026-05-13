@extends('layouts.modern')

@section('page-title', 'Plans & Pricing')

@php
    $activeNav = 'home';
    $useAppShell = true;
@endphp

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('Plans & Pricing') }}</h1>
                    <p class="modern-page-subtitle">Planes y precios</p>
                </div>
            </header>

            <section style="padding: 0 1.5rem;">
                @include('partials.modern.pricing')
            </section>
        </div>
    </main>
@endsection
