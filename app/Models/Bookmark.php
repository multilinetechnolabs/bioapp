<?php

namespace App\Models;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

class Bookmark extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'url'
    ];

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'name' => 'required|string|unique_with:bookmarks,user_id',
        'url' => 'required|string'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
