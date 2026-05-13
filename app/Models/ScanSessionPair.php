<?php

namespace App\Models;

class ScanSessionPair extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scan_session_id', 'pair_id', 'user_id'
    ];
    
    public static function rules($id = null)
    {
        return [
            'scan_session_id' => 'required|integer',
            'pair_id' => 'required|integer',
            'user_id' => 'required|integer'
        ];
    }

    public function scanSession()
    {
        return $this->belongsTo(ScanSession::class);
    }

    public function pair()
    {
        return $this->belongsTo(Pair::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = ['pair', 'scanSession', 'user'];

    public function getPairAttribute()
    {
        return $this->pair()->first();
    }

    public function getScanSessionAttribute()
    {
        return $this->scanSession()->first();
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }
}
