<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    protected $fillable = ['course_module_id', 'title', 'body', 'type', 'order', 'lesson_group'];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function images()
    {
        return $this->hasMany(CourseLessonImage::class);
    }

    public function videos()
    {
        return $this->hasMany(CourseLessonVideo::class)->orderBy('order');
    }
}
