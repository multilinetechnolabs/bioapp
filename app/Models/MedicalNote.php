<?php

namespace App\Models;

use Watson\Validating\Injectors\UniqueWithInjector;
use Watson\Validating\ValidatingTrait;

class MedicalNote extends Base
{
    use ValidatingTrait, UniqueWithInjector;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'date_noted', 'description'
    ];

    protected $rules = [
        'client_id' => 'required|integer|exists:clients,id',
        'date_noted' => 'required|date|unique_with:medical_notes,client_id,date_noted',
        'description' => 'required|string|unique_with:medical_notes,client_id,date_noted'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected $appends = ['client'];

    public function getClientAttribute()
    {
        return $this->client()->first();
    }
}
