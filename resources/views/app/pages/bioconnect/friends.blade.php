@extends('layouts.modern')

@section('page-title', 'Bio Connect Friends')

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
                <h1 class="modern-page-title">Bio Connect Friends / Amigos de Bio Connect</h1>
            </div>
            <div class="modern-page-header__actions">
                @if (!empty(Auth::user()))
                    <a href="{{ url('/bioconnect/profile') }}" class="modern-btn modern-btn--outline">My Profile / Mi Perfil</a>
                @else
                    <a href="{{ url('/login') }}" class="modern-btn modern-btn--primary">Sign In / Iniciar sesión</a>
                @endif
            </div>
        </header>

        @if (!empty(Auth::user()))
            <div class="row g-4 modern-bioconnect-layout"
                 ng-controller="BioConnectFriendsCtrl as ctrl" ng-cloak>

                <div class="col-12 col-lg-3">
                    @include('partials.bioconnect.friends_menu')
                </div>

                <div class="col-12 col-lg-9">
                    <section class="modern-info-card friends-container modern-bioconnect-friends">
                        <div class="loader" ng-if="!ctrl.userFriendsLoaded"></div>

                        <h6 class="text-center mt-4"
                            ng-if="!(ctrl.user_friends | valPresent) && ctrl.userFriendsLoaded">
                            You have no friends yet. / Aún no tienes amigos.
                        </h6>

                        <div class="friends-grid"
                             ng-if="(ctrl.user_friends | valPresent) && ctrl.userFriendsLoaded">
                            <div class="friend-item"
                                 ng-repeat="user_friend in ctrl.user_friends track by user_friend.id">
                                <div class="friend-card">
                                    <div class="text-center">
                                        <img ng-src="<% user_friend.friend.profilePictureUrl %>" class="friend-avatar">
                                    </div>
                                    <div class="friend-info text-center">
                                        <h6 class="friend-name"><% user_friend.friend.name %></h6>
                                        <p class="text-muted mb-1">
                                            <% user_friend.friend.location || 'No location' %>
                                        </p>
                                        <p class="text-muted">
                                            <% user_friend.friend.address || 'No address' %>
                                        </p>
                                    </div>
                                    <div class="friend-actions">
                                        <button class="btn btn-primary btn-sm w-100 mb-2 sidebarSecondary_toggle show-chat-box"
                                                data-chatid="<% user_friend.friend.id %>"
                                                data-chatname="<% user_friend.friend.name %>"
                                                data-chatprofileimageurl="<% user_friend.friend.profilePictureUrl %>">
                                            Message
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm w-100"
                                                ng-click="ctrl.deleteFriend(user_friend)">
                                            Unfriend / Eliminar amigo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="friends-scroll-status"
                             ng-if="ctrl.userFriendsLoaded && ctrl.userFriendsLoadingMore">
                            <div class="loader loader-inline"></div>
                        </div>
                    </section>
                </div>
            </div>
        @else
            <section class="modern-info-card friends-container">
                <div class="friends-grid">
                    @foreach (\App\Models\User::where('privacy', false)->orderBy('name', 'asc')->get() as $user)
                        <div class="friend-item">
                            <div class="friend-card text-center">
                                <img src="{{ $user->profilePictureUrl }}" class="friend-avatar">
                                <h6 class="friend-name">{{ $user->name }}</h6>
                                <p class="text-muted mb-1">
                                    {{ $user->location ?? 'No location' }}
                                </p>
                                <p class="text-muted">
                                    {{ $user->address ?? 'No address' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    @include('partials.bioconnect.chat_box')
@endsection

@push('scripts')
    @if (!empty(Auth::user()))
        @include('partials.bioconnect.friends_chat')
    @endif
@endpush
