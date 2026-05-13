<?php

namespace App\Models\Activity;

use App\Models\Base;

use Watson\Validating\ValidatingTrait;

class Category extends Base
{
    use ValidatingTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected $rules = [
        'name' => 'required|string|unique:activity_categories,name'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }
}
