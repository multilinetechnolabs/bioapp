<?php

namespace App\Models;

use App\Models\Base;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

use App\Models\Post\Category as PostCategory;

class PostPostCategory extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'post_category_id',
    ];

    protected $rules = [
        'post_id' => 'required|integer|exists:posts,id',
        'post_category_id' => 'required|integer|exists:post_categories,id|unique_with:post_post_categories,post_id',
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function post_category()
    {
        return $this->belongsTo(PostCategory::class);
    }
}
