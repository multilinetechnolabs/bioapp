@extends('layouts.modern')

@section('page-title', 'My Discussions')

@php
    $activeNav = 'connect';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/bioconnect.css') }}" rel="stylesheet">
    <link href="{{ \App\Support\VersionedAsset::url('css/app/bioconnect/groups.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content">
        <header class="modern-page-header">
            <div>
                <h1 class="modern-page-title" id="discussionMode">My Discussions / Mis Discusiones</h1>
                <p class="modern-page-subtitle">Discussions you have started / Discusiones que has iniciado</p>
            </div>
            <div class="modern-page-header__actions">
                <a href="{{ url('/bioconnect/groups') }}" class="modern-btn modern-btn--outline">
                    <span aria-hidden="true">&larr;</span> Recent Discussions / Discusiones recientes
                </a>
            </div>
        </header>

        <span id="discussionUserId" style="display: none;" data-value="{{ Auth::user()->id }}"></span>

        <div class="row g-4 modern-bioconnect-layout"
             ng-controller="BioConnectDiscussionsCtrl as ctrl" ng-cloak>
            <div class="col-12 col-lg-8 order-2 order-lg-1">
                <section class="modern-info-card modern-bioconnect-discussions">
                    @include('partials.bioconnect.discussions_area')
                </section>
            </div>
            <div class="col-12 col-lg-4 order-1 order-lg-2">
                @include('partials.bioconnect.discussion_types_box')
                <section class="modern-info-card modern-bioconnect-submit-card">
                    @include('partials.bioconnect.discussion_submit_box')
                </section>
            </div>
        </div>
    </main>
@endsection
