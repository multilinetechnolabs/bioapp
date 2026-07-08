<?php

namespace App\Http\Controllers;

use App\Support\CourseContent;
use Auth;

class CourseController extends Controller
{
    const SESSION_KEY = 'course_preview.completed';
    const PAID_KEY = 'course_preview.paid';

    public function checkout()
    {
        return view('app.pages.course.checkout', [
            'alreadyPaid' => (bool) session(self::PAID_KEY),
            'status' => request('status'),
        ]);
    }

    public function pay()
    {
        session([
            self::PAID_KEY => true,
            'course_preview.purchased_at' => now()->toDateTimeString(),
        ]);

        return redirect()->route('course.checkout', ['status' => 'success']);
    }

    public function payFailed()
    {
        return redirect()->route('course.checkout', ['status' => 'failed']);
    }

    public function index()
    {
        $completed = $this->completedLessons();
        $moduleStates = [];

        foreach (CourseContent::modules() as $number => $module) {
            $lessonCount = count($module['slides']);
            $completedInModule = $this->completedCountInModule($number, $completed);

            $moduleStates[] = [
                'number' => $number,
                'title' => $module['module_title'],
                'lesson_count' => $lessonCount,
                'completed_count' => $completedInModule,
                'progress_percent' => $lessonCount ? (int) round($completedInModule / $lessonCount * 100) : 0,
                'unlocked' => $this->isModuleUnlocked($number, $completed),
                'completed' => $lessonCount > 0 && $completedInModule === $lessonCount,
            ];
        }

        $totalLessons = CourseContent::totalLessons();
        $totalCompleted = count($completed);

        return view('app.pages.course.index', [
            'moduleStates' => $moduleStates,
            'totalLessons' => $totalLessons,
            'totalCompleted' => $totalCompleted,
            'overallPercent' => $totalLessons ? (int) round($totalCompleted / $totalLessons * 100) : 0,
            'courseComplete' => $totalLessons > 0 && $totalCompleted >= $totalLessons,
        ]);
    }

    public function module(int $module)
    {
        $moduleData = CourseContent::module($module);
        abort_unless($moduleData, 404);

        $completed = $this->completedLessons();
        $moduleUnlocked = $this->isModuleUnlocked($module, $completed);

        $lessons = [];
        foreach ($moduleData['slides'] as $slide) {
            $lessons[] = $this->decorateLesson($module, $slide, $completed);
        }

        return view('app.pages.course.module', [
            'moduleNumber' => $module,
            'moduleTitle' => $moduleData['module_title'],
            'lessons' => $lessons,
            'moduleUnlocked' => $moduleUnlocked,
            'prevModule' => CourseContent::previousModule($module),
            'nextModule' => CourseContent::nextModule($module),
        ]);
    }

    public function lesson(int $module, int $lesson)
    {
        $moduleData = CourseContent::module($module);
        abort_unless($moduleData, 404);

        $slide = CourseContent::lesson($module, $lesson);
        abort_unless($slide, 404);

        $completed = $this->completedLessons();
        $unlocked = $this->isLessonUnlocked($module, $lesson, $completed);

        $lessonCount = CourseContent::lessonCount($module);
        $prev = $lesson > 1 ? $lesson - 1 : null;
        $next = $lesson < $lessonCount ? $lesson + 1 : null;

        $prevModule = $prev ? null : CourseContent::previousModule($module);
        $nextModule = $next ? null : CourseContent::nextModule($module);

        $navModules = [];
        foreach (CourseContent::modules() as $number => $m) {
            $navLessons = [];
            foreach ($m['slides'] as $s) {
                $navLessons[] = $this->decorateLesson($number, $s, $completed);
            }
            $navModules[] = [
                'number' => $number,
                'title' => $m['module_title'],
                'unlocked' => $this->isModuleUnlocked($number, $completed),
                'lessons' => $navLessons,
            ];
        }

        return view('app.pages.course.lesson', [
            'moduleNumber' => $module,
            'moduleTitle' => $moduleData['module_title'],
            'lesson' => $slide,
            'lessonIndex' => $lesson,
            'lessonCount' => $lessonCount,
            'unlocked' => $unlocked,
            'prev' => $prev,
            'next' => $next,
            'prevModule' => $prevModule,
            'nextModule' => $nextModule,
            'isCompleted' => in_array($this->lessonKey($module, $lesson), $completed, true),
            'navModules' => $navModules,
        ]);
    }

    public function markComplete(int $module, int $lesson)
    {
        $moduleData = CourseContent::module($module);
        abort_unless($moduleData, 404);
        abort_unless(CourseContent::lesson($module, $lesson), 404);

        $completed = $this->completedLessons();
        $key = $this->lessonKey($module, $lesson);

        if (!in_array($key, $completed, true)) {
            $completed[] = $key;
            session([self::SESSION_KEY => $completed]);
        }

        $lessonCount = CourseContent::lessonCount($module);

        if ($lesson < $lessonCount) {
            return redirect()->route('course.lesson', [$module, $lesson + 1]);
        }

        $nextModule = CourseContent::nextModule($module);

        if ($nextModule) {
            return redirect()->route('course.lesson', [$nextModule, 1]);
        }

        return redirect()->route('course.certificate');
    }

    public function certificate()
    {
        $totalLessons = CourseContent::totalLessons();
        $completed = $this->completedLessons();
        $isComplete = $totalLessons > 0 && count($completed) >= $totalLessons;

        return view('app.pages.course.certificate', [
            'isComplete' => $isComplete,
            'completedCount' => count($completed),
            'totalLessons' => $totalLessons,
            'completionDate' => now()->format('F j, Y'),
        ]);
    }

    public function resetProgress()
    {
        session()->forget(self::SESSION_KEY);

        return redirect()->route('course.index');
    }

    public function removeAccess()
    {
        session()->forget([self::PAID_KEY, 'course_preview.purchased_at']);

        return redirect()->route('course.checkout');
    }

    protected function completedLessons(): array
    {
        return session(self::SESSION_KEY, []);
    }

    protected function lessonKey(int $module, int $lesson): string
    {
        return "{$module}-{$lesson}";
    }

    protected function completedCountInModule(int $module, array $completed): int
    {
        $count = 0;

        foreach (range(1, CourseContent::lessonCount($module)) as $i) {
            if (in_array($this->lessonKey($module, $i), $completed, true)) {
                $count++;
            }
        }

        return $count;
    }

    protected function isModuleUnlocked(int $module, array $completed): bool
    {
        $prevModule = CourseContent::previousModule($module);

        if (!$prevModule) {
            return true;
        }

        $prevLessonCount = CourseContent::lessonCount($prevModule);

        return $prevLessonCount > 0 && $this->completedCountInModule($prevModule, $completed) >= $prevLessonCount;
    }

    protected function isLessonUnlocked(int $module, int $lesson, array $completed): bool
    {
        if (!$this->isModuleUnlocked($module, $completed)) {
            return false;
        }

        if ($lesson === 1) {
            return true;
        }

        return in_array($this->lessonKey($module, $lesson - 1), $completed, true);
    }

    protected function decorateLesson(int $module, array $slide, array $completed): array
    {
        $slide['module_number'] = $module;
        $slide['unlocked'] = $this->isLessonUnlocked($module, (int) $slide['index'], $completed);
        $slide['completed'] = in_array($this->lessonKey($module, (int) $slide['index']), $completed, true);

        return $slide;
    }
}
