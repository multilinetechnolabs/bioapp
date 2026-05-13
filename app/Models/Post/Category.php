<?php

namespace App\Models\Post;

use App\Models\Base;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

use App\Models\Post;
use App\Models\PostPostCategory;

class Category extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug',
    ];

    protected $rules = [
        'title' => 'required|string|unique_with:post_categories,slug',
        'slug' => 'required|string|unique:post_categories,slug',
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($postCategory) {
            foreach ($postCategory->postPostCategories()->get() as $postPostCategory) {
                $postPostCategory->delete();
            }
        });
    }

    public function postPostCategories()
    {
        return $this->hasMany(PostPostCategory::class, 'post_category_id');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_post_categories', 'post_id', 'post_category_id');
    }
}
