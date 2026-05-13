<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class Logo extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'file_name', 's3_name'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($logo) {
            $partial_url = 'logos/uid-'.$logo->user_id.'/'.$logo->s3_name;
            if (\Storage::exists($partial_url)) {
                \Storage::delete('/'.$partial_url);
            }
        });
    }

    protected $rules = [
        'user_id' => 'required|integer|exists:users,id',
        'file_name' => 'required|string',
        's3_name' => 'required|string|unique:logos,s3_name'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file_url()
    {
        $partial_url = "users/uid-".$this->user_id."/logos/".$this->s3_name;
        if (!empty($this->s3_name) && \Storage::exists($partial_url)) {
            return $this->awsAssetsUrl("/".$partial_url);
        } else {
            return asset('/images/iconimages/file_not_found.png');
        }
    }

    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return $this->file_url();
    }
}
