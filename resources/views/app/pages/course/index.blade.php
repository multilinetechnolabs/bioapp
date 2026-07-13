@extends('layouts.modern')

@section('page-title', 'Certification Course')

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

            @if (!$hasCourse)
                <div class="course-panel">
                    <div class="course-locked-state">
                        <div class="course-locked-state__icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
                        <h2>No Course Available Yet</h2>
                        <p>We're still setting things up here. Please check back soon.</p>
                        <a href="{{ route('app.dashboard') }}" class="course-btn course-btn--outline">Back to Dashboard</a>
                    </div>
                </div>
            @else
            <div class="course-hero">
                <div>
                    <h1 class="course-hero__title">{{ $courseTitle }}</h1>
                    <p class="course-hero__subtitle">Master advanced Chakra Mapping and Biomagnetic Pair protocols across {{ count($moduleStates) }} modules. Complete each module in order to unlock the next, and earn your certificate at the end.</p>
                    <div class="course-hero__reset">
                        <form action="{{ route('course.reset') }}" method="POST" onsubmit="return confirm('Reset your demo progress? This clears all Mark Complete checkpoints so you can replay the course from the start.');">
                            @csrf
                            <button type="submit">Reset demo progress</button>
                        </form>
                        <form action="{{ route('course.removeAccess') }}" method="POST" onsubmit="return confirm('Remove course access? You will need to pay again to re-enter.');" style="margin-top:4px;">
                            @csrf
                            <button type="submit">Remove access (test re-pay)</button>
                        </form>
                    </div>
                </div>
                <div class="course-hero__progress">
                    <div class="course-hero__progress-label">
                        <span>Overall progress</span>
                        <span>{{ $totalCompleted }} / {{ $totalLessons }} ({{ $overallPercent }}%)</span>
                    </div>
                    <div class="course-progress">
                        <div class="course-progress__fill" style="width: {{ $overallPercent }}%;"></div>
                    </div>
                </div>
            </div>

            <div class="course-modules-grid">
                @foreach ($moduleStates as $m)
                    @php
                        $statusClass = $m['completed'] ? 'complete' : ($m['completed_count'] > 0 ? 'in-progress' : 'locked');
                        $firstLessonUrl = route('course.lesson', [$m['number'], $m['resume_lesson']]);
                    @endphp
                    <div class="course-module-card {{ !$m['unlocked'] ? 'course-module-card--locked' : '' }}">
                        <div class="course-module-card__top">
                            <span class="course-module-card__number">Module {{ $m['number'] }}</span>
                            <span class="course-module-card__status course-module-card__status--{{ $m['unlocked'] ? $statusClass : 'locked' }}">
                                @if (!$m['unlocked'])
                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                @elseif ($m['completed'])
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                @else
                                    {{ $m['completed_count'] }}/{{ $m['lesson_count'] }}
                                @endif
                            </span>
                        </div>
                        <h2 class="course-module-card__title">{{ $m['title'] }}</h2>
                        <div class="course-progress">
                            <div class="course-progress__fill" style="width: {{ $m['progress_percent'] }}%;"></div>
                        </div>
                        <div class="course-module-card__meta">{{ $m['lesson_count'] }} lessons</div>
                        <div class="course-module-card__cta">
                            @if (!$m['unlocked'])
                                <button class="course-btn course-btn--outline course-btn--block" disabled>
                                    <i class="fa fa-lock" aria-hidden="true"></i> Locked
                                </button>
                            @else
                                <a href="{{ $firstLessonUrl }}" class="course-btn course-btn--primary course-btn--block">
                                    {{ $m['completed'] ? 'Review' : ($m['completed_count'] > 0 ? 'Continue' : 'Start') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="course-certificate-cta">
                <div>
                    <h3><i class="fa fa-certificate" aria-hidden="true"></i> Certificate of Completion</h3>
                    <p>{{ $courseComplete ? 'Congratulations — your certificate is ready.' : 'Finish every module to unlock your certificate.' }}</p>
                </div>
                <a href="{{ route('course.certificate') }}" class="course-btn course-btn--outline">
                    {{ $courseComplete ? 'View Certificate' : 'View Progress' }}
                </a>
            </div>

            <p style="text-align:center;font-size:.72rem;color:var(--course-ink-soft);margin-top:18px;">
                &copy; {{ date('Y') }} Anew Avenue Biomagnetism. All rights reserved. For personal educational use only.
            </p>
            @endif

        </div>
    </div>
</main>
@endsection
