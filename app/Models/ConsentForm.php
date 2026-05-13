<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class ConsentForm extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'date_entered', 'file_name', 's3_name', 'description'
    ];

    protected $rules = [
        'client_id' => 'required|integer|exists:clients,id',
        'file_name' => 'required|string',
        's3_name' => 'required|string|unique:consent_forms,s3_name',
        'description' => 'nullable|string'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($consent_form) {
            $partial_url = 'clients/uid-'.$consent_form->client_id.'/consent_forms/'.$consent_form->s3_name;
            if (\Storage::exists($partial_url)) {
                \Storage::delete('/'.$partial_url);
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function file_url()
    {
        return $this->awsAssetsUrl('/clients/uid-'.$this->client_id.'/consent_forms/'.$this->s3_name);
    }

    protected $appends = ['file_ext', 'file_url'];

    public function getFileUrlAttribute()
    {
        return $this->file_url();
    }

    public function getFileExtAttribute()
    {
        return array_last(explode('.', $this->file_name));
    }
}
