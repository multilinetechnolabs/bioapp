{{-- Expects: $navModules, $moduleNumber, $lessonIndex --}}
<nav class="course-lesson-nav" id="courseLessonNav">
    @foreach ($navModules as $navModule)
        <div class="course-nav-module {{ !$navModule['unlocked'] ? 'course-nav-module--locked' : '' }}">
            <div class="course-nav-module__title">
                @if (!$navModule['unlocked'])
                    <i class="fa fa-lock" aria-hidden="true"></i>
                @endif
                Module {{ $navModule['number'] }}: {{ $navModule['title'] }}
            </div>
            @if ($navModule['unlocked'])
                @foreach ($navModule['lessons'] as $navLesson)
                    @php
                        $isCurrent = $navModule['number'] === $moduleNumber && $navLesson['index'] === $lessonIndex;
                        $label = trim(str_replace("\n", ' ', $navLesson['heading'] ?? 'Lesson ' . $navLesson['index']));
                        $classes = 'course-nav-lesson';
                        $classes .= $isCurrent ? ' course-nav-lesson--current' : '';
                        $classes .= $navLesson['completed'] ? ' course-nav-lesson--complete' : '';
                        $classes .= !$navLesson['unlocked'] ? ' course-nav-lesson--locked' : '';
                    @endphp
                    @if ($navLesson['unlocked'])
                        <a href="{{ route('course.lesson', [$navModule['number'], $navLesson['index']]) }}" class="{{ $classes }}">
                            <span class="course-nav-lesson__dot"></span>
                            <span>{{ $navLesson['index'] }}. {{ \Illuminate\Support\Str::limit($label, 42) }}</span>
                        </a>
                    @else
                        <span class="{{ $classes }}">
                            <span class="course-nav-lesson__dot"></span>
                            <span>{{ $navLesson['index'] }}. {{ \Illuminate\Support\Str::limit($label, 42) }}</span>
                        </span>
                    @endif
                @endforeach
            @endif
        </div>
    @endforeach
</nav>
