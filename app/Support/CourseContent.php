<?php

namespace App\Support;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;

class CourseContent
{
    protected static $modules = null;

    public static function modules(): array
    {
        if (self::$modules !== null) {
            return self::$modules;
        }

        $course = Course::where('is_active', true)
            ->with(['modules.lessons.images', 'modules.lessons.videos'])
            ->first();

        $modules = [];

        if ($course) {
            foreach ($course->modules as $module) {
                $modules[$module->order] = self::shapeModule($module);
            }
        }

        return self::$modules = $modules;
    }

    protected static function shapeModule(CourseModule $module): array
    {
        $slides = [];

        foreach ($module->lessons as $lesson) {
            $slides[] = self::shapeLesson($lesson);
        }

        return [
            'module_title' => $module->title,
            'slides' => $slides,
        ];
    }

    protected static function shapeLesson(CourseLesson $lesson): array
    {
        // Keep every language variant so the frontend can switch images to match
        // whichever language the user has selected (GTranslate only translates
        // text, never text baked into an image, hence the per-language uploads).
        $imagesByLang = $lesson->images->pluck('path', 'language')->all();
        $defaultImage = $imagesByLang['en'] ?? $lesson->images->first()?->path;

        return [
            'id' => $lesson->id,
            'index' => $lesson->order,
            'heading' => $lesson->title,
            'body' => $lesson->body,
            'type' => $lesson->type,
            'image_source' => $defaultImage,
            'images_by_lang' => $imagesByLang,
            'video_files' => $lesson->videos->isEmpty() ? null : $lesson->videos->pluck('url')->all(),
        ];
    }

    public static function moduleNumbers(): array
    {
        return array_keys(self::modules());
    }

    public static function module(int $moduleNumber): ?array
    {
        return self::modules()[$moduleNumber] ?? null;
    }

    public static function lesson(int $moduleNumber, int $lessonIndex): ?array
    {
        $module = self::module($moduleNumber);

        if (!$module) {
            return null;
        }

        foreach ($module['slides'] as $slide) {
            if ((int) $slide['index'] === $lessonIndex) {
                return $slide;
            }
        }

        return null;
    }

    public static function lessonCount(int $moduleNumber): int
    {
        $module = self::module($moduleNumber);

        return $module ? count($module['slides']) : 0;
    }

    public static function totalLessons(): int
    {
        $total = 0;

        foreach (self::modules() as $module) {
            $total += count($module['slides']);
        }

        return $total;
    }

    public static function previousModule(int $moduleNumber): ?int
    {
        $numbers = self::moduleNumbers();
        $pos = array_search($moduleNumber, $numbers, true);

        return $pos !== false && $pos > 0 ? $numbers[$pos - 1] : null;
    }

    public static function nextModule(int $moduleNumber): ?int
    {
        $numbers = self::moduleNumbers();
        $pos = array_search($moduleNumber, $numbers, true);

        return $pos !== false && isset($numbers[$pos + 1]) ? $numbers[$pos + 1] : null;
    }
}
