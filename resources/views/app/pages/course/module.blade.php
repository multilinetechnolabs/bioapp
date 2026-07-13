@extends('layouts.modern')

@section('page-title', $moduleTitle)

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

            <div class="course-breadcrumb">
                <a href="{{ route('course.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Course</a>
                <span>&rsaquo;</span>
                <span>Module {{ $moduleNumber }}</span>
            </div>

            <div class="course-panel">
                <div class="course-panel__header">
                    <div>
                        <span class="course-module-card__number">Module {{ $moduleNumber }}</span>
                        <h1>{{ $moduleTitle }}</h1>
                    </div>
                    @if ($moduleUnlocked && !empty($lessons))
                        <a href="{{ route('course.lesson', [$moduleNumber, $resumeLesson]) }}" class="course-btn course-btn--primary">
                            {{ $resumeLesson > 1 ? 'Continue Module' : 'Start Module' }}
                        </a>
                    @endif
                </div>

                @if (!$moduleUnlocked)
                    <div class="course-locked-state">
                        <div class="course-locked-state__icon"><i class="fa fa-lock" aria-hidden="true"></i></div>
                        <h2>This module is locked</h2>
                        <p>Complete every lesson in the previous module to unlock this one.</p>
                        @if ($prevModule)
                            <a href="{{ route('course.module', $prevModule) }}" class="course-btn course-btn--outline">
                                Go to Module {{ $prevModule }}
                            </a>
                        @endif
                    </div>
                @else
                    <ul class="course-lesson-list">
                        @foreach ($lessons as $lesson)
                            @php
                                $typeIcon = ['title' => 'fa-align-left', 'text' => 'fa-align-left', 'image' => 'fa-picture-o', 'mixed' => 'fa-picture-o', 'video' => 'fa-play-circle-o'][$lesson['type']] ?? 'fa-align-left';
                                $rowLabel = trim(str_replace("\n", ' ', $lesson['heading'] ?? 'Lesson ' . $lesson['index']));
                            @endphp
                            <li>
                                @if ($lesson['unlocked'])
                                    <a href="{{ route('course.lesson', [$moduleNumber, $lesson['index']]) }}" class="course-lesson-row">
                                        <span class="course-lesson-row__icon {{ $lesson['completed'] ? 'course-lesson-row__icon--complete' : '' }}">
                                            @if ($lesson['completed'])
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                            @else
                                                {{ $lesson['index'] }}
                                            @endif
                                        </span>
                                        <span class="course-lesson-row__body">
                                            <div class="course-lesson-row__title">{{ $rowLabel }}</div>
                                            <div class="course-lesson-row__type"><i class="fa {{ $typeIcon }}" aria-hidden="true"></i> {{ $lesson['type'] }}</div>
                                        </span>
                                    </a>
                                @else
                                    <span class="course-lesson-row course-lesson-row--locked">
                                        <span class="course-lesson-row__icon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                        <span class="course-lesson-row__body">
                                            <div class="course-lesson-row__title">{{ $rowLabel }}</div>
                                            <div class="course-lesson-row__type">Locked</div>
                                        </span>
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <p style="text-align:center;font-size:.72rem;color:var(--course-ink-soft);margin-top:18px;">
                &copy; {{ date('Y') }} Anew Avenue Biomagnetism. All rights reserved. For personal educational use only.
            </p>

        </div>
    </div>
</main>
@endsection
