<?php

namespace App\Models;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

class Playlist extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'description'
    ];

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'name' => 'required|string|unique_with:playlists,user_id',
        'description' => 'nullable|string'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($playlist) {
            foreach ($playlist->mediaPlaylists()->get() as $mediaPlaylist) {
                $mediaPlaylist->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mediaPlaylists()
    {
        return $this->hasMany(MediaPlaylist::class);
    }

    public function medias()
    {
        return $this->belongsToMany(Media::class, 'media_playlists');
    }
}
