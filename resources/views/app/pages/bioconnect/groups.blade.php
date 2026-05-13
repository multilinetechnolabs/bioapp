@extends('layouts.modern')

@section('page-title', 'Group Discussions')

@php
    $activeNav = 'connect';
    $useAppShell = !empty(Auth::user());
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/bioconnect.css') }}" rel="stylesheet">
    <link href="{{ \App\Support\VersionedAsset::url('css/app/bioconnect/groups.css') }}" rel="stylesheet">
@endpush

@section('content')
    <main class="modern-main-content">
        @if (!empty(Auth::user()))
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title" id="discussionMode">Recent Discussions / Discusiones recientes</h1>
                    <p class="modern-page-subtitle">Group conversations / Conversaciones grupales</p>
                </div>
            </header>

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
        @else
            <header class="modern-page-header">
                <div>
                    <h1 class="modern-page-title" id="discussionMode">Discussions / Discusiones</h1>
                    <p class="modern-page-subtitle">Public discussions / Discusiones públicas</p>
                </div>
            </header>

            <section class="modern-info-card modern-bioconnect-discussions">
                <div class="container-fluid px-0" ng-cloak>
                    <table class="table">
                        <tbody id="postcontentid" class="post_content">
                            @foreach (App\Models\GroupDiscussion::all() as $discussion)
                                <tr>
                                    <th>
                                        <div class="clearfix post-container">
                                            <img class="img-responsive profile-image" src="{{ $discussion->creator->profilePictureUrl }}" alt="{{ $discussion->creator->name }}">
                                            <p style="text-align: justify; font-weight: 500;">{{ $discussion->discussion }}</p>
                                            <p class="post-name">{{ $discussion->creator->name }}</p>
                                            <span class="post-time">{{ $discussion->created_at }}</span>
                                            @if ($discussion->comments->count() > 0)
                                                <div class="comment-container">
                                                    @foreach ($discussion->comments as $comment)
                                                        <div class="comment-content">
                                                            <p>
                                                                <img class="comment-user-image" src="{{ $comment->creator->profilePictureUrl }}" alt="{{ $comment->creator->name }}">
                                                                <strong>{{ $comment->creator->name }}</strong>{{ $comment->content }}<span class="comment-time">{{ $comment->created_at }}</span>
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </main>
@endsection
