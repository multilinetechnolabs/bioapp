<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLessonImage extends Model
{
    protected $fillable = ['course_lesson_id', 'language', 'path'];

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }
}
