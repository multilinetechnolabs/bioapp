<?php

namespace App\Models;

use App\Models\Base;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

use App\Models\Post\Category as PostCategory;

class Post extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_author', 'post_content', 'post_title', 'post_excerpt', 'post_status', 'comment_status',
        'post_slug', 'post_type', 'published_at', 'post_featured_img', 'post_featured_md_id',
        'seo_title', 'seo_meta_description', 'seo_meta_keywords'
    ];

    protected $rules = [
        'post_author' => 'required|integer|exists:users,id',
        'post_content' => 'required|string|unique_with:posts,title',
        'post_title' => 'required|string|unique_with:posts,post_slug',
        'post_excerpt' => 'nullable|string',
        'comment_status' => 'required|boolean',
        'post_slug' => 'required|string|unique:posts,post_slug',
        'post_type' => 'required|string',
        'post_featured_img' => 'nullable|string',
        'post_featured_md_id' => 'nullable|integer',
        'seo_title' => 'nullable|string',
        'seo_meta_description' => 'nullable|string',
        'seo_meta_keywords' => 'nullable|string',
        'published_at' => 'nullable|date',
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            foreach ($post->postPostCategories()->get() as $postPostCategory) {
                $postPostCategory->delete();
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'post_author');
    }

    public function postPostCategories()
    {
        return $this->hasMany(PostPostCategory::class);
    }

    public function categories()
    {
        return $this->belongsToMany(PostCategory::class, 'post_post_categories', 'post_id', 'post_category_id');
    }

    public function isPublished()
    {
        return !empty($this->published_at);
    }

    protected $appends = ['isPublished'];

    public function getIsPublishedAttribute()
    {
        return $this->isPublished();
    }
}
