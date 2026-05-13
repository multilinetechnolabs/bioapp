<?php

namespace App\Models\Discussion;

use App\Models\Base;
use App\Models\GroupDiscussion;
use App\Models\User;

use Watson\Validating\ValidatingTrait;

class Comment extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'discussion_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'discussion_id', 'user_id', 'content'
    ];

    protected $rules = [
        'discussion_id' => 'required|integer|exists:group_discussions,id',
        'user_id' => 'required|integer|exists:users,id',
        'content' => 'required|string'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function discussion()
    {
        return $this->belongsTo(GroupDiscussion::class);
    }

    protected $appends = ['creator'];
    
    public function getCreatorAttribute()
    {
        return $this->creator()->first();
    }
}
