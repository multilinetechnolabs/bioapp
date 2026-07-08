<?php

namespace App\Support;

class CourseContent
{
    protected static $modules = null;

    public static function modules(): array
    {
        if (self::$modules !== null) {
            return self::$modules;
        }

        $modules = [];
        $path = resource_path('data/course_content');

        foreach (range(1, 9) as $n) {
            $file = $path . "/module_{$n}.json";

            if (!file_exists($file)) {
                continue;
            }

            $data = json_decode(file_get_contents($file), true);

            if (is_array($data)) {
                $modules[$n] = $data;
            }
        }

        return self::$modules = $modules;
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
