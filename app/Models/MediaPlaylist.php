<?php

namespace App\Models;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

class MediaPlaylist extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'media_id', 'playlist_id'
    ];

    protected $rules = [
        'media_id' => 'required|integer|exists:media,id|unique_with:media_playlists,playlist_id',
        'playlist_id' => 'required|integer|exists:playlists,id|unique_with:media_playlists,playlist_id'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
}
