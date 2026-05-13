<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class Pair extends Base
{
    use ValidatingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'radical', 'origins', 'symptoms', 'paths', 'alternative_routes', 'scan_type',
        'ref_no', 'guided_ref_no'
    ];

    protected $rules = [
        'name' => 'required|string',
        'radical' => 'nullable|string',
        'origins' => 'nullable|string',
        'symptoms' => 'nullable|string',
        'paths' => 'nullable|string',
        'alternative_routes' => 'nullable|string',
        'scan_type' => 'required|string',
        'ref_no' => 'nullable|string',
        'guided_ref_no' => 'nullable|string'
    ];

    public static function rules($id = null)
    {
        return (new static)->rules;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($pair) {
            foreach ($pair->clientPairs() as $clientPair) {
                $clientPair->delete();
            }

            foreach ($pair->scanSessionPairs()->get() as $scanSessionPair) {
                $scanSessionPair->delete();
            }
        });
    }

    public function clientPairs()
    {
        return $this->hasMany(ClientPair::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_pairs');
    }

    public function scanSessionPairs()
    {
        return $this->hasMany(ScanSessionPair::class);
    }

    public function scanSessions()
    {
        return $this->belongsToMany(ScanSession::class, 'scan_session_pairs');
    }

    protected $appends = ['referenceNumber', 'guidedReferenceNumber'];

    public function getReferenceNumberAttribute()
    {
        return (int)$this->ref_no;
    }

    public function getGuidedReferenceNumberAttribute()
    {
        return (int)$this->guided_ref_no;
    }
}
