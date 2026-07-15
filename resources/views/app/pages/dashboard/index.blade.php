@extends('layouts.modern')

@section('page-title', 'Dashboard')

@php
    $activeNav   = 'home';
    $useAppShell = true;
@endphp

@section('content')
<div class="dash-page">

    <h1 class="dash-greeting">
        Welcome back, <span class="dash-greeting__name">{{ Auth::user()->name }}</span>.
        <span class="dash-greeting__es">Bienvenido de nuevo.</span>
    </h1>

    <header class="modern-page-header">
        <div>
            <h1 class="modern-page-title">Getting Started & Setup Tips</h1>
            <p class="modern-page-subtitle">Consejos de inicio y configuración</p>
        </div>
        <a href="{{ url('/data_cache/help') }}" class="modern-btn modern-btn--outline">
            <span aria-hidden="true">?</span> Help / Ayuda
        </a>
    </header>

    <div class="dash-grid mt-3">

        {{-- Body Scan --}}
        <a href="{{ route('app.bodyscan') }}" class="dash-card" style="--card-color:#10b981;--card-bg:#d1fae5;">
            <div class="dash-card__row">
                <svg class="dash-card__ekg" viewBox="0 0 60 24" fill="none" stroke="var(--card-color)" stroke-width="1.5" aria-hidden="true">
                    <path d="M0,12 L10,12 L14,4 L18,20 L22,8 L26,16 L30,12 L60,12"/>
                </svg>
                <div class="dash-card__icon-wrap">
                    <svg width="28" height="28" fill="none" stroke="var(--card-color)" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </div>
                <svg class="dash-card__ekg" viewBox="0 0 60 24" fill="none" stroke="var(--card-color)" stroke-width="1.5" aria-hidden="true">
                    <path d="M0,12 L30,12 L34,4 L38,20 L42,8 L46,16 L50,12 L60,12"/>
                </svg>
            </div>
            <div class="dash-card__title">Body Scan</div>
            <div class="dash-card__subtitle">Escaneo Corporal</div>
        </a>

        {{-- Chakra Map --}}
        <a href="{{ route('app.chakrascan') }}" class="dash-card" style="--card-color:#7c3aed;--card-bg:#ede9fe;">
            <div class="dash-card__row">
                <svg class="dash-card__ekg" viewBox="0 0 60 24" fill="none" stroke="var(--card-color)" stroke-width="1.5" aria-hidden="true">
                    <path d="M0,12 L10,12 L14,4 L18,20 L22,8 L26,16 L30,12 L60,12"/>
                </svg>
                <div class="dash-card__icon-wrap">
                    <svg width="28" height="28" fill="none" stroke="var(--card-color)" viewBox="0 0 100 100" stroke-width="1.2" aria-hidden="true">
                        <circle cx="50" cy="50" r="32"/><circle cx="50" cy="50" r="16"/><circle cx="66" cy="50" r="16"/><circle cx="58" cy="36.1" r="16"/><circle cx="42" cy="36.1" r="16"/><circle cx="34" cy="50" r="16"/><circle cx="42" cy="63.9" r="16"/><circle cx="58" cy="63.9" r="16"/>
                    </svg>
                </div>
                <svg class="dash-card__ekg" viewBox="0 0 60 24" fill="none" stroke="var(--card-color)" stroke-width="1.5" aria-hidden="true">
                    <path d="M0,12 L30,12 L34,4 L38,20 L42,8 L46,16 L50,12 L60,12"/>
                </svg>
            </div>
            <div class="dash-card__title">Chakra Map</div>
            <div class="dash-card__subtitle">Mapa Chakra</div>
        </a>

        {{-- My Vault (Data Cache) --}}
        <a href="{{ route('app.data_cache') }}" class="dash-card" style="--card-color:#3b82f6;--card-bg:#dbeafe;">
            <div class="dash-card__row">
                <svg class="dash-card__ekg" viewBox="0 0 60 24" fill="none" stroke="var(--card-color)" stroke-width="1.5" aria-hidden="true">
                    <path d="M0,12 L10,12 L14,4 L18,20 L22,8 L26,16 L30,12 L60,12"/>
                </svg>
                <div class="dash-card__icon-wrap">
                    <svg width="28" height="28" fill="none" stroke="var(--card-color)" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </div>
                <svg class="dash-card__ekg" viewBox="0 0 60 24" fill="none" stroke="var(--card-color)" stroke-width="1.5" aria-hidden="true">
                    <path d="M0,12 L30,12 L34,4 L38,20 L42,8 L46,16 L50,12 L60,12"/>
                </svg>
            </div>
            <div class="dash-card__title">Data</div>
            <div class="dash-card__subtitle">Datos</div>
        </a>

        {{-- The Circle (BioConnect) --}}
        <a href="{{ route('app.bioconnect') }}" class="dash-card" style="--card-color:#f97316;--card-bg:#ffedd5;">
            <div class="dash-card__row">
                <svg class="dash-card__ekg" viewBox="0 0 60 24" fill="none" stroke="var(--card-color)" stroke-width="1.5" aria-hidden="true">
                    <path d="M0,12 L10,12 L14,4 L18,20 L22,8 L26,16 L30,12 L60,12"/>
                </svg>
                <div class="dash-card__icon-wrap">
                    <svg width="28" height="28" fill="none" stroke="var(--card-color)" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </div>
                <svg class="dash-card__ekg" viewBox="0 0 60 24" fill="none" stroke="var(--card-color)" stroke-width="1.5" aria-hidden="true">
                    <path d="M0,12 L30,12 L34,4 L38,20 L42,8 L46,16 L50,12 L60,12"/>
                </svg>
            </div>
            <div class="dash-card__title">Connect</div>
            <div class="dash-card__subtitle">Conectar</div>
        </a>

    </div>

    {{-- Course banner (purchasers only) --}}
    @php
        $dashCourse = \App\Models\Course::where('is_active', true)->first();
        $dashHasCourseAccess = $dashCourse && \App\Models\CoursePurchase::userHasAccess(Auth::id(), $dashCourse->id);
    @endphp
    @if ($dashHasCourseAccess)
        <style>
            .dash-course-banner{background:linear-gradient(135deg,#0f766e,#0d9488);border-radius:18px;padding:22px 26px;color:#fff;display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between;text-decoration:none;margin-top:1.25rem;}
            .dash-course-banner:hover{color:#fff;text-decoration:none;}
            .dash-course-banner h3{font-family:Georgia,serif;margin:0 0 4px;font-size:1.25rem;}
            .dash-course-banner p{margin:0;opacity:.9;font-size:.9rem;}
            .dash-course-banner__btn{background:#fff;color:#0f766e;font-weight:700;border:none;border-radius:999px;padding:11px 22px;font-size:.9rem;white-space:nowrap;}
        </style>
        <a href="{{ route('course.index') }}" class="dash-course-banner">
            <div>
                <h3>Your Course: {{ $dashCourse->title }}</h3>
                <p>Continue where you left off.</p>
            </div>
            <span class="dash-course-banner__btn">Go to Course</span>
        </a>
    @endif

</div>
@endsection
