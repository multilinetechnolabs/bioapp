@extends('layouts.modern')

@section('page-title', 'Find Friends')

@php
    $activeNav = 'connect';
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/bioconnect.css') }}" rel="stylesheet">
    <link href="{{ \App\Support\VersionedAsset::url('css/app/bioconnect-card.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content">
        <header class="modern-page-header">
            <div>
                <h1 class="modern-page-title">Find Friends / Buscar Amigos</h1>
                <p class="modern-page-subtitle">Discover and invite new connections / Descubre e invita nuevas conexiones</p>
            </div>
            <div class="modern-page-header__actions">
                <a href="{{ url('/bioconnect/friends') }}" class="modern-btn modern-btn--outline">
                    <span aria-hidden="true">&larr;</span> All Friends / Todos los amigos
                </a>
            </div>
        </header>

        <div class="row g-4 modern-bioconnect-layout"
             ng-controller="BioConnectFindFriendsCtrl as ctrl" ng-cloak>

            <div class="col-12 col-lg-3">
                @include('partials.bioconnect.friends_menu')
            </div>

            <div class="col-12 col-lg-9">
                <section class="modern-info-card friends-container modern-bioconnect-friends">
                    <div class="search-container">
                        <div class="search-inline">
                            <i class="fa fa-search"></i>
                            <input type="text"
                                   placeholder="Search friends..."
                                   ng-model="ctrl.searchText">
                        </div>
                    </div>

                    <div class="loader" ng-if="!ctrl.usersLoaded"></div>

                    <h6 class="text-center mt-4"
                        ng-if="!(ctrl.users | valPresent) && ctrl.usersLoaded">
                        No results found.
                    </h6>

                    <div class="friends-grid" ng-if="(ctrl.users | valPresent) && ctrl.usersLoaded">
                        <div class="friend-item"
                             ng-repeat="user in ctrl.users track by user.id">
                            <div class="friend-card">
                                <div class="text-center">
                                    <img ng-src="<% user.profilePictureUrl %>" class="friend-avatar">
                                </div>
                                <div class="friend-info text-center">
                                    <h6 class="friend-name"><% user.name %></h6>
                                    <p class="text-muted mb-1">
                                        <% user.location || 'No location' %>
                                    </p>
                                    <p class="text-muted">
                                        <% user.address || 'No address' %>
                                    </p>
                                </div>
                                <div class="friend-actions">
                                    <button class="btn btn-primary btn-sm w-100" ng-click="ctrl.addFriend(user)">
                                        Invite / Invitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="friends-scroll-status"
                         ng-if="ctrl.usersLoaded && ctrl.usersLoadingMore">
                        <div class="loader loader-inline"></div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    @include('partials.bioconnect.chat_box')
@endsection
