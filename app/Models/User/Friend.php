<?php

namespace App\Models\User;

use App\Models\Base;
use App\Models\User;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

class Friend extends Base
{
    use ValidatingTrait, UniqueWithInjector;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_friends';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'friend_id', 'accepted'
    ];

    public static function boot()
    {
        parent::boot();
    }

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'friend_id' => 'required|integer|exists:users,id',
        'accepted' => 'required|boolean'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    protected $appends = ['friend'];

    public function getFriendAttribute()
    {
        if (\Auth::user()->id == $this->user_id) {
            return $this->friend()->first();
        } else {
            return $this->user()->first();
        }
    }
}
