@extends('layouts.modern')

@section('page-title', 'Client Info')

@php
    $activeNav = 'data';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/data_cache.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content modern-main-content--fluid">
        <div class="modern-data-cache-wrap">
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title">{{ __('Client Info') }}</h1>
                    <p class="modern-page-subtitle">Información del cliente</p>
                </div>
                <div class="modern-page-header__actions">
                    <a href="{{ route('app.data_cache') }}" class="modern-btn modern-btn--outline">
                        <span aria-hidden="true">&larr;</span> Back to Data Cache / Volver
                    </a>
                    <button type="button" class="modern-btn modern-btn--primary"
                            data-toggle="modal" data-target="#clientInfoModal" data-title="New Client">
                        <span aria-hidden="true">+</span>
                        <span>{{ __('Create New Client') }} / Crear nuevo cliente</span>
                    </button>
                </div>
            </header>

            <section id="content-container" class="data-cache-client-page">
                <div class="modern-info-card data-cache-client-panel">
                    <div class="modern-data-cache-table-shell data-cache-client-table-shell">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-datatable" id="clients">
                                <thead>
                                    <tr>
                                        <th class="align-center">{{ __('ID') }}</th>
                                        <th class="align-center">{{ __('First Name') }} / Nombre</th>
                                        <th class="align-center">{{ __('Last Name') }} / Apellido</th>
                                        <th class="align-center">{{ __('Email') }} / Correo</th>
                                        <th class="align-center">{{ __('Address') }} / Dirección</th>
                                        <th class="align-center">{{ __('Phone No.') }} / Teléfono</th>
                                        <th class="align-center">{{ __('Date of Birth') }} / Fecha de nacimiento</th>
                                        <th class="align-center">{{ __('Age') }} / Edad</th>
                                        <th class="align-center">{{ __('Emergency Details') }} / Emergencia</th>
                                        <th class="align-center">{{ __('Session Details') }} / Sesión</th>
                                        <th class="align-center">{{ __('Actions') }} / Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    @include('app.pages.data_cache.modals.client_info')
@endsection

@push('scripts')
    @include('app.pages.data_cache.modals.js.client_info')
@endpush
