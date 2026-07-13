@extends('layouts.modern')

@section('page-title', 'Certificate of Completion')

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
                <span>Certificate</span>
            </div>

            @if (!$isComplete)
                <div class="course-panel">
                    <div class="course-locked-state">
                        <div class="course-locked-state__icon"><i class="fa fa-certificate" aria-hidden="true"></i></div>
                        <h2>Your certificate isn't ready yet</h2>
                        <p>Complete all lessons to unlock your Certificate of Completion.</p>
                        <div style="max-width:360px;margin:18px auto;">
                            <div class="course-hero__progress-label">
                                <span>Progress</span>
                                <span>{{ $completedCount }} / {{ $totalLessons }}</span>
                            </div>
                            <div class="course-progress">
                                <div class="course-progress__fill" style="width: {{ $totalLessons ? round($completedCount / $totalLessons * 100) : 0 }}%;"></div>
                            </div>
                        </div>
                        <a href="{{ route('course.index') }}" class="course-btn course-btn--primary">Back to Course</a>
                    </div>
                </div>
            @else
                @php($accent = $template->accent_color ?: '#14b8a6')
                <div class="course-certificate-wrap">
                    <div class="course-certificate" style="border-color: {{ $accent }};">
                        <div class="course-certificate__seal" style="background: radial-gradient(circle at 35% 30%, {{ $accent }}, var(--course-primary-dark));"><i class="fa fa-certificate" aria-hidden="true"></i></div>
                        <div class="course-certificate__eyebrow">{{ $template->render($template->cert_eyebrow, $certData) }}</div>
                        <h1 class="course-certificate__title">{!! nl2br(e($template->render($template->cert_title, $certData))) !!}</h1>
                        <p class="course-certificate__intro">{{ $template->render($template->cert_intro, $certData) }}</p>
                        <div class="course-certificate__name" style="border-color: {{ $accent }};">{{ $certData['name'] }}</div>
                        <p class="course-certificate__desc">{{ $template->render($template->cert_body, $certData) }}</p>
                        @if (!empty($template->cert_disclaimer))
                            <p class="course-certificate__desc" style="font-size:.78rem;font-style:italic;">
                                {{ $template->render($template->cert_disclaimer, $certData) }}
                            </p>
                        @endif
                        <div class="course-certificate__footer">
                            <div><strong>Date issued</strong><br>{{ $completionDate }}</div>
                            <div><strong>{{ $template->issuer_name }}</strong><br>{{ $template->issuer_email }}</div>
                        </div>
                        <div class="course-certificate__actions">
                            <a href="{{ route('course.certificate.download') }}" class="course-btn course-btn--primary">
                                <i class="fa fa-download" aria-hidden="true"></i> Download Certificate (PDF)
                            </a>
                        </div>
                    </div>
                </div>
                @if ($template->badge_enabled)
                <div style="max-width:340px;margin:32px auto 0;text-align:center;background:#fff;border:1px solid var(--course-border);border-radius:16px;padding:24px;">
                    <h3 style="font-family:var(--course-font-serif);color:var(--course-primary-dark);font-size:1.05rem;margin:0 0 14px;">Your Digital Badge</h3>
                    <div style="width:130px;height:130px;margin:0 auto 14px;border-radius:50%;background:radial-gradient(circle at 35% 30%,{{ $accent }},#0f766e);display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(15,118,110,.35);border:4px solid #d1fae5;">
                        <div style="color:#fff;text-align:center;line-height:1.2;">
                            <i class="fa fa-certificate" aria-hidden="true" style="font-size:1.6rem;display:block;margin-bottom:4px;"></i>
                            <span style="font-size:.62rem;font-weight:700;letter-spacing:.04em;">{{ $template->badge_label }}</span>
                        </div>
                    </div>
                    <p style="font-size:.86rem;color:var(--course-ink-soft);margin:0 0 4px;">"{{ $template->render($template->badge_caption, $certData) }}"</p>
                    <p style="font-size:.72rem;color:var(--course-ink-soft);margin:0;">{{ $template->render($template->badge_subtext, $certData) }}</p>
                </div>
                @endif

                <p style="text-align:center;font-size:.72rem;color:var(--course-ink-soft,#64748b);margin-top:14px;">
                    &copy; {{ date('Y') }} {{ $template->issuer_name }}. All rights reserved. For personal educational use only.
                </p>
            @endif

        </div>
    </div>
</main>
@endsection
