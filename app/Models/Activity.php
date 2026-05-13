<?php

namespace App\Models;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

class Activity extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category', 'title', 'content', 'date_published'
    ];

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'category' => 'required|string',
        'title' => 'required|string|unique_with:activities,user_id,date_published',
        'content' => 'required|string|unique_with:activities,title,date_published',
        'date_published' => 'required|date'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityCategory()
    {
        return $this->belongsTo(Activity\Category::class, 'category', 'name');
    }

    protected $appends = ['user'];

    public function getUserAttribute()
    {
        return $this->user()->first();
    }
}
