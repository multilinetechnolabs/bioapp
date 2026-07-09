@extends('layouts.modern')

@section('page-title', 'Course Access')

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
                    <div class="course-locked-state__icon"><i class="fa fa-lock" aria-hidden="true"></i></div>
                    <h2>Purchase Required</h2>
                    <p>Get access to the Biomagnetism Certification Course to start learning.</p>
                    <a href="{{ route('course.checkout') }}" class="course-btn course-btn--primary">
                        <i class="fa fa-lock" aria-hidden="true"></i> Pay &amp; Unlock Course
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
