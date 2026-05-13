<?php

namespace App\Models;

class GroupDiscussion extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'discussion', 'dis_type', 'dis_order', 'dis_status', 'created_by', 'updated_by'
    ];

    public static function rules($id = null)
    {
        return [
            'discussion' => 'required|string',
            'created_by' => 'required|integer'
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($discussion) {
            foreach ($discussion->comments()->get() as $comment) {
                $comment->delete();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Discussion\Comment::class, 'discussion_id', 'id');
    }

    protected $appends = ['creator', 'comments', 'comments_count'];
    
    public function getCreatorAttribute()
    {
        return $this->creator()->first();
    }

    public function getCommentsAttribute()
    {
        return $this->comments()->get();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
}
