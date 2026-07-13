@extends('layouts.modern')

@section('page-title', $moduleTitle . ' — Lesson ' . $lessonIndex)

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
                <a href="{{ route('course.module', $moduleNumber) }}">Module {{ $moduleNumber }}</a>
                <span>&rsaquo;</span>
                <span>Lesson {{ $lessonIndex }}</span>
            </div>

            @if (!$unlocked)
                <div class="course-panel">
                    <div class="course-locked-state">
                        <div class="course-locked-state__icon"><i class="fa fa-lock" aria-hidden="true"></i></div>
                        <h2>This lesson is locked</h2>
                        <p>Complete the previous lesson to unlock this one.</p>
                        <a href="{{ route('course.module', $moduleNumber) }}" class="course-btn course-btn--outline">
                            Back to Module {{ $moduleNumber }}
                        </a>
                    </div>
                </div>
            @else
                <div class="course-lesson-progressbar">
                    <div class="course-lesson-progressbar__label">
                        <span>Module {{ $moduleNumber }} &mdash; Lesson {{ $lessonIndex }} of {{ $lessonCount }}</span>
                        @if ($isCompleted)
                            <span class="course-completed-badge"><i class="fa fa-check" aria-hidden="true"></i> Completed</span>
                        @endif
                    </div>
                    <div class="course-progress">
                        <div class="course-progress__fill" style="width: {{ $lessonCount ? round($lessonIndex / $lessonCount * 100) : 0 }}%;"></div>
                    </div>
                </div>

                <button type="button" class="course-nav-toggle" id="courseNavToggle" aria-expanded="false">
                    <i class="fa fa-bars" aria-hidden="true"></i> Lesson list
                </button>

                <div class="course-lesson-layout">
                    @include('app.pages.course.partials.lesson_nav')

                    <div class="course-lesson-divider" aria-hidden="true"></div>

                    <div class="course-lesson-content">
                        @if (!empty($lesson['heading']))
                            <h1 class="course-lesson-content__heading">{{ $lesson['heading'] }}</h1>
                        @endif

                        @if (!empty($lesson['body']))
                            <div class="course-lesson-content__body">{{ $lesson['body'] }}</div>
                        @endif

                        @if (!empty($lesson['image_source']))
                            <div class="course-lesson-media">
                                <img
                                    src="{{ asset(ltrim(str_replace('public/', '', $lesson['image_source']), '/')) }}"
                                    class="course-lang-image"
                                    @foreach (($lesson['images_by_lang'] ?? []) as $lang => $path)
                                        data-lang-src-{{ $lang }}="{{ asset(ltrim(str_replace('public/', '', $path), '/')) }}"
                                    @endforeach
                                    alt="{{ \Illuminate\Support\Str::limit(strip_tags($lesson['heading'] ?? 'Lesson diagram'), 80) }}">
                            </div>
                        @endif

                        @if (!empty($lesson['video_files']))
                            <div class="course-lesson-media">
                                <div class="course-lesson-videos {{ count($lesson['video_files']) > 1 ? 'course-lesson-videos--multi' : '' }}">
                                    @foreach ($lesson['video_files'] as $vi => $videoFile)
                                        @php($isEmbed = \Illuminate\Support\Str::startsWith($videoFile, ['http://', 'https://']))
                                        <div class="course-video-block">
                                            @if ($isEmbed)
                                                <div class="course-video-embed">
                                                    <iframe
                                                        src="{{ $videoFile }}"
                                                        loading="lazy"
                                                        allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;fullscreen;"
                                                        allowfullscreen>
                                                    </iframe>
                                                </div>
                                            @else
                                                <video controls preload="metadata">
                                                    <source src="{{ asset($videoFile) }}" type="video/mp4">
                                                </video>
                                                <div class="course-caption-toggle" data-video-index="{{ $vi }}">
                                                    <span class="course-caption-toggle__label"><i class="fa fa-cc" aria-hidden="true"></i> Captions</span>
                                                    <button type="button" class="is-active" data-lang="en">EN</button>
                                                    <button type="button" data-lang="es">ES</button>
                                                    <button type="button" data-lang="fr">FR</button>
                                                    <button type="button" data-lang="off">Off</button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if (collect($lesson['video_files'])->contains(fn ($v) => !\Illuminate\Support\Str::startsWith($v, ['http://', 'https://'])))
                                    <p style="font-size:.76rem;color:var(--course-ink-soft, #64748b);margin-top:8px;">
                                        Caption language selection shown for design review only — live multi-language captions are generated by Bunny Stream once video hosting is connected.
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div class="course-help-box">
                            <p><strong>Didn't understand this lesson?</strong><br>Email us and we'll help you through it.</p>
                            <a class="course-btn course-btn--outline course-btn--small"
                               href="mailto:{{ config('mail.from.address') }}?subject={{ rawurlencode('Question about Module ' . $moduleNumber . ' — Lesson ' . $lessonIndex . ': ' . trim(str_replace("\n", ' ', $lesson['heading'] ?? ''))) }}&body={{ rawurlencode("Hi Anew Avenue team,\n\nI have a question about Module {$moduleNumber}, Lesson {$lessonIndex}.\n\nMy question:\n") }}">
                                <i class="fa fa-envelope-o" aria-hidden="true"></i> Email about this lesson
                            </a>
                        </div>

                        <div class="course-lesson-actions">
                            <div class="course-lesson-actions__nav">
                                @if ($prev)
                                    <a href="{{ route('course.lesson', [$moduleNumber, $prev]) }}" class="course-btn course-btn--ghost">
                                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Previous
                                    </a>
                                @elseif ($prevModule)
                                    <a href="{{ route('course.module', $prevModule) }}" class="course-btn course-btn--ghost">
                                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Module {{ $prevModule }}
                                    </a>
                                @endif
                            </div>
                            <form action="{{ route('course.lesson.complete', [$moduleNumber, $lessonIndex]) }}" method="POST">
                                @csrf
                                <button type="submit" class="course-btn course-btn--primary">
                                    @if ($isCompleted && ($next || $nextModule))
                                        Next <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                    @elseif (!$next && !$nextModule)
                                        Finish Course <i class="fa fa-certificate" aria-hidden="true"></i>
                                    @else
                                        Mark Complete &amp; Continue <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                    @endif
                                </button>
                            </form>
                        </div>

                        <p style="font-size:.72rem;color:var(--course-ink-soft);margin-top:24px;">
                            &copy; {{ date('Y') }} Anew Avenue Biomagnetism. All rights reserved. For personal educational use only. Unauthorized reproduction, distribution, or teaching of this proprietary content is strictly prohibited.
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</main>

@push('scripts')
<script>
(function () {
    var toggle = document.getElementById('courseNavToggle');
    var nav = document.getElementById('courseLessonNav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    document.querySelectorAll('.course-caption-toggle').forEach(function (group) {
        group.querySelectorAll('button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                group.querySelectorAll('button').forEach(function (b) { b.classList.remove('is-active'); });
                btn.classList.add('is-active');
            });
        });
    });

    // Swap lesson diagrams to match the selected language. GTranslate only
    // translates text — it can't touch text baked into an image — so each
    // diagram is uploaded per-language and we pick the matching file here.
    // The language switcher doesn't reload the page when going TO es/fr (only
    // when going back to en), so this can't rely on a fresh page load alone —
    // it polls localStorage for a change instead.
    function applyCourseImageLocale() {
        var lang = 'en';
        try { lang = localStorage.getItem('app_locale') || 'en'; } catch (e) {}

        document.querySelectorAll('.course-lang-image').forEach(function (img) {
            var target = img.getAttribute('data-lang-src-' + lang) || img.getAttribute('data-lang-src-en');
            if (target && img.getAttribute('src') !== target) {
                img.setAttribute('src', target);
            }
        });
    }

    applyCourseImageLocale();

    var _courseImgLastLang = null;
    try { _courseImgLastLang = localStorage.getItem('app_locale'); } catch (e) {}

    setInterval(function () {
        var cur = null;
        try { cur = localStorage.getItem('app_locale'); } catch (e) {}
        if (cur !== _courseImgLastLang) {
            _courseImgLastLang = cur;
            applyCourseImageLocale();
        }
    }, 400);
})();
</script>
@endpush
@endsection
