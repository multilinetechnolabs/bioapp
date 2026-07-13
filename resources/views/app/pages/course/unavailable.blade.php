@extends('layouts.modern')

@section('page-title', 'Course')

@php
    $useAppShell = true;
@endphp

@push('head')
    <link href="{{ \App\Support\VersionedAsset::url('css/app/course.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="modern-main-content modern-main-content--fluid">
    <div class="course-shell">
        <div class="course-container">
            <div class="course-panel">
                <div class="course-locked-state">
                    <div class="course-locked-state__icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
                    <h2>No Course Available Yet</h2>
                    <p>We're still setting things up here. Please check back soon.</p>
                    <a href="{{ route('app.dashboard') }}" class="course-btn course-btn--outline">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
