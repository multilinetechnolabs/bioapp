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
                <a href="{{ route('course.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Course / Curso</a>
                <span>&rsaquo;</span>
                <span>Certificate / Certificado</span>
            </div>

            @if (!$isComplete)
                <div class="course-panel">
                    <div class="course-locked-state">
                        <div class="course-locked-state__icon"><i class="fa fa-certificate" aria-hidden="true"></i></div>
                        <h2>Your certificate isn't ready yet / Tu certificado aún no está listo</h2>
                        <p>Complete all lessons to unlock your Certificate of Completion.<br>Completa todas las lecciones para desbloquear tu certificado.</p>
                        <div style="max-width:360px;margin:18px auto;">
                            <div class="course-hero__progress-label">
                                <span>Progress / Progreso</span>
                                <span>{{ $completedCount }} / {{ $totalLessons }}</span>
                            </div>
                            <div class="course-progress">
                                <div class="course-progress__fill" style="width: {{ $totalLessons ? round($completedCount / $totalLessons * 100) : 0 }}%;"></div>
                            </div>
                        </div>
                        <a href="{{ route('course.index') }}" class="course-btn course-btn--primary">Back to Course / Volver al curso</a>
                    </div>
                </div>
            @else
                <div class="course-certificate-wrap">
                    <div class="course-certificate course-print-area">
                        <div class="course-certificate__seal"><i class="fa fa-certificate" aria-hidden="true"></i></div>
                        <div class="course-certificate__eyebrow">Certificate of Completion &middot; Certificado de finalización</div>
                        <h1 class="course-certificate__title">Anew Avenue Biomagnetism<br>Certification Course</h1>
                        <p class="course-certificate__intro">This certifies that / Esto certifica que</p>
                        <div class="course-certificate__name">{{ Auth::user()->name ?? 'Student Name' }}</div>
                        <p class="course-certificate__desc">has successfully completed all {{ $totalLessons }} lessons of the Anew Avenue Biomagnetism Certification Course, demonstrating mastery of advanced Chakra Mapping and Biomagnetic Pair protocols.</p>
                        <div class="course-certificate__footer">
                            <div><strong>Date issued / Fecha</strong><br>{{ $completionDate }}</div>
                            <div><strong>Anew Avenue Biomagnetism</strong><br>anewavenuebio@gmail.com</div>
                        </div>
                        <div class="course-certificate__actions">
                            <button type="button" class="course-btn course-btn--primary" onclick="window.print()">
                                <i class="fa fa-print" aria-hidden="true"></i> Print / Download / Imprimir
                            </button>
                        </div>
                    </div>
                </div>
                <p style="text-align:center;font-size:.76rem;color:var(--course-ink-soft,#64748b);margin-top:14px;">
                    Design preview — the final version generates a downloadable PDF automatically. / Vista previa de diseño.
                </p>
            @endif

        </div>
    </div>
</main>
@endsection
