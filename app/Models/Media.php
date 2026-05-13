<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class Media extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_name', 's3_name', 'description', 'user_id'
    ];

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'file_name' => 'required|string',
        's3_name' => 'required|string|unique:media,s3_name',
        'description' => 'nullable|string'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($media) {
            foreach ($media->audioStoragePaths() as $partialUrl) {
                if (\Storage::exists($partialUrl)) {
                    \Storage::delete('/'.$partialUrl);
                }
            }

            foreach ($media->mediaPlaylists()->get() as $mediaPlaylist) {
                $mediaPlaylist->delete();
            }
        });
    }

    public function file_url()
    {
        $partialUrl = $this->resolveAudioStoragePath();

        if (! $partialUrl) {
            return null;
        }

        return $this->audioUrlForPath($partialUrl);
    }

    public function resolveAudioStoragePath(array $availablePaths = null)
    {
        $availableLookup = null;

        if ($availablePaths !== null) {
            $availableLookup = array_fill_keys($availablePaths, true);
        }

        foreach ($this->audioStoragePaths() as $partialUrl) {
            if ($availableLookup !== null) {
                if (! isset($availableLookup[$partialUrl])) {
                    continue;
                }
            } elseif (! \Storage::exists($partialUrl)) {
                continue;
            }

            if ($partialUrl !== 'audio_files/'.$this->s3_name) {
                \Log::info('Using fallback media path for audio file.', [
                    'media_id' => $this->id,
                    'file_name' => $this->file_name,
                    's3_name' => $this->s3_name,
                    'resolved_path' => $partialUrl,
                ]);
            }

            return $partialUrl;
        }

        return null;
    }

    public function audioUrlForPath($partialUrl)
    {
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', ltrim($partialUrl, '/'))));

        return $this->awsAssetsUrl('/'.$encodedPath);
    }

    protected function audioStoragePaths()
    {
        $paths = [];

        if (! empty($this->s3_name)) {
            $paths[] = 'audio_files/'.$this->s3_name;

            $normalizedS3Name = preg_replace('/^\d+_/', '', $this->s3_name);

            if (! empty($normalizedS3Name)) {
                $paths[] = 'audio_files/'.$normalizedS3Name;
            }
        }

        $fileName = trim((string) $this->file_name);

        if ($fileName !== '') {
            $paths[] = 'audio_files/'.$fileName;

            if (! preg_match('/\.(mp3|wav|m4a)$/i', $fileName)) {
                $paths[] = 'audio_files/'.$fileName.'.mp3';
            }
        }

        return array_values(array_unique($paths));
    }

    public function mediaPlaylists()
    {
        return $this->hasMany(MediaPlaylist::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'media_playlists');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute()
    {
        return $this->file_url();
    }
}
