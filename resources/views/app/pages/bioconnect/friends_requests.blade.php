@extends('layouts.modern')

@section('page-title', 'Friend Requests')

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
                <h1 class="modern-page-title">Friend Requests / Solicitudes de amistad</h1>
                <p class="modern-page-subtitle">Accept or decline pending requests / Acepta o rechaza solicitudes pendientes</p>
            </div>
            <div class="modern-page-header__actions">
                <a href="{{ url('/bioconnect/friends') }}" class="modern-btn modern-btn--outline">
                    <span aria-hidden="true">&larr;</span> All Friends / Todos los amigos
                </a>
            </div>
        </header>

        <div class="row g-4 modern-bioconnect-layout"
             ng-controller="BioConnectFriendRequestsCtrl as ctrl" ng-cloak>

            <div class="col-12 col-lg-3">
                @include('partials.bioconnect.friends_menu')
            </div>

            <div class="col-12 col-lg-9">
                <section class="modern-info-card friends-container modern-bioconnect-friends">
                    <div class="loader" ng-if="!ctrl.friendRequestsLoaded"></div>

                    <h6 class="text-center mt-4"
                        ng-if="!(ctrl.friend_requests | valPresent) && ctrl.friendRequestsLoaded">
                        You have no friend requests.
                    </h6>

                    <div class="friends-grid"
                         ng-if="(ctrl.friend_requests | valPresent) && ctrl.friendRequestsLoaded">
                        <div class="friend-item"
                             ng-repeat="friend_request in ctrl.friend_requests track by friend_request.id">
                            <div class="friend-card">
                                <div class="text-center">
                                    <img ng-src="<% friend_request.friend.profilePictureUrl %>" class="friend-avatar">
                                </div>
                                <div class="friend-info text-center">
                                    <h6 class="friend-name"><% friend_request.friend.name %></h6>
                                    <p class="text-muted mb-1">
                                        <% friend_request.friend.location || 'No location' %>
                                    </p>
                                    <p class="text-muted mb-1">
                                        Age: <% friend_request.friend.age || '-' %>
                                    </p>
                                    <p class="text-muted">
                                        <% friend_request.friend.address || 'No address' %>
                                    </p>
                                </div>
                                <div class="friend-actions">
                                    <button class="btn btn-success btn-sm w-100 mb-2"
                                            ng-click="ctrl.acceptFriendRequest(friend_request)">
                                        Accept
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm w-100"
                                            ng-click="ctrl.rejectFriendRequest(friend_request)">
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="friends-scroll-status"
                         ng-if="ctrl.friendRequestsLoaded && ctrl.friendRequestsLoadingMore">
                        <div class="loader loader-inline"></div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    @include('partials.bioconnect.chat_box')
@endsection
