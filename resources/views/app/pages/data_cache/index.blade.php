@extends('layouts.modern')

@section('page-title', 'Data Cache')

@php
    $activeNav = 'data';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/data_cache.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="dc-page">
    <main class="modern-main-content">
        <header class="modern-page-header">
            <div>
                <h1 class="modern-page-title">Data Cache</h1>
                <p class="modern-page-subtitle">Caché de datos</p>
            </div>
            <a href="{{ url('/data_cache/help') }}" class="modern-btn modern-btn--outline">
                <span aria-hidden="true">?</span> Help / Ayuda
            </a>
        </header>

        <div class="row g-4 modern-data-cache-grid">
            <div class="col-12 col-md-6">
                <a href="{{ route('app.bodyscan') }}" class="modern-data-cache-tile">
                    <div class="modern-data-cache-tile__icon">
                        <svg class="modern-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                    <div class="modern-data-cache-tile__label">
                        <span class="data-cache-label-en">Bio</span>
                        <span class="data-cache-label-es">Bio</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6">
                <a href="{{ url('/data_cache/client_info') }}" class="modern-data-cache-tile">
                    <div class="modern-data-cache-tile__icon">
                        <svg class="modern-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                    <div class="modern-data-cache-tile__label">
                        <span class="data-cache-label-en">Client Info</span>
                        <span class="data-cache-label-es">Información del cliente</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6">
                <a href="{{ route('app.chakrascan') }}" class="modern-data-cache-tile">
                    <div class="modern-data-cache-tile__icon">
                        <svg class="modern-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 100 100" aria-hidden="true" stroke-width="1.2">
                            <circle cx="50" cy="50" r="32"/><circle cx="50" cy="50" r="16"/><circle cx="66" cy="50" r="16"/><circle cx="58" cy="36.1" r="16"/><circle cx="42" cy="36.1" r="16"/><circle cx="34" cy="50" r="16"/><circle cx="42" cy="63.9" r="16"/><circle cx="58" cy="63.9" r="16"/>
                        </svg>
                    </div>
                    <div class="modern-data-cache-tile__label">
                        <span class="data-cache-label-en">Chakra</span>
                        <span class="data-cache-label-es">Chakra</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6">
                <button type="button" class="modern-data-cache-tile modern-data-cache-tile--button"
                        data-toggle="modal" data-target="#preferencesModal">
                    <div class="modern-data-cache-tile__icon">
                        <svg class="modern-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                    <div class="modern-data-cache-tile__label">
                        <span class="data-cache-label-en">Preferences</span>
                        <span class="data-cache-label-es">Preferencias</span>
                    </div>
                </button>
            </div>

          
        </div>
    </main>
</div>

    @include('app.pages.data_cache.modals.client_info')
    @include('app.pages.data_cache.modals.preferences')
@endsection

@push('scripts')
    @include('app.pages.data_cache.modals.js.preferences')
@endpush
