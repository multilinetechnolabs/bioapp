<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'price', 'is_active', 'order'];

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('order');
    }
}
