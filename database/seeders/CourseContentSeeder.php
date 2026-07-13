<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use Illuminate\Database\Seeder;

class CourseContentSeeder extends Seeder
{
    public function run()
    {
        $course = Course::updateOrCreate(['title' => 'Biomagnetism Certification Course'], [
            'description' => 'Full access to the certification course, plus Body Scan & Chakra Scan tools, for 1 year.',
            'price' => 297,
            'is_active' => true,
            'order' => 1,
        ]);

        foreach (range(1, 9) as $n) {
            $path = resource_path("data/course_content/module_{$n}.json");

            if (!file_exists($path)) {
                continue;
            }

            $data = json_decode(file_get_contents($path), true);

            $module = CourseModule::updateOrCreate(
                ['course_id' => $course->id, 'title' => $data['module_title']],
                ['order' => $n]
            );

            foreach ($data['slides'] as $slide) {
                $lesson = CourseLesson::updateOrCreate(
                    ['course_module_id' => $module->id, 'order' => $slide['index']],
                    [
                        'title' => $slide['heading'] ?? null,
                        'body' => $slide['body'] ?? null,
                        'type' => $slide['type'],
                        'lesson_group' => $slide['lesson_group'] ?? null,
                    ]
                );

                $lesson->images()->delete();
                if (!empty($slide['image_source'])) {
                    $lesson->images()->create([
                        'language' => 'en',
                        'path' => str_replace('public/', '', $slide['image_source']),
                    ]);
                }

                $lesson->videos()->delete();
                if (!empty($slide['video_files'])) {
                    foreach ($slide['video_files'] as $i => $videoFile) {
                        $lesson->videos()->create([
                            'url' => 'figma/' . $videoFile,
                            'order' => $i + 1,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Course content seeded: ' . CourseModule::count() . ' modules, ' . CourseLesson::count() . ' lessons.');
    }
}
