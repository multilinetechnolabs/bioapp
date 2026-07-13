<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLessonVideo extends Model
{
    protected $fillable = ['course_lesson_id', 'url', 'order'];

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }
}
