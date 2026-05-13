<?php

namespace App\Models;

use Auth;
use DateTime;
use Excel;

use App\Exports\ScanSessionExport;

class ScanSession extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'user_id', 'scan_type', 'date_started', 'date_ended', 'cost_type', 'cost'
    ];

    public static function rules($id = null)
    {
        return [
            'client_id' => 'required|integer|exists:clients,id',
            'user_id' => 'required|integer|exists:users,id',
            'scan_type' => 'required|string',
            'date_started' => 'required|datetime',
            'date_ended' => 'nullable|datetime'
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($scan_session) {
            foreach ($scan_session->scanSessionPairs()->get() as $scanSessionPair) {
                $scanSessionPair->delete();
            }
        });

    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'resource');
    }

    public function paid()
    {
        return !empty($this->payment);
    }

    public function description()
    {
        return ucwords(str_replace('_', ' ', $this->scan_type)).' Session on '.$this->date_started.' for '.$this->client->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pairs()
    {
        return $this->belongsToMany(Pair::class, 'scan_session_pairs');
    }

    public function scanSessionPairs()
    {
        return $this->hasMany(ScanSessionPair::class);
    }

    public function pairIds()
    {
        return $this->pairs()->get()->pluck('id');
    }

    public function export()
    {
        $date = new DateTime();
        $timestamp = $date->format('YmdHis');
        $filename = 'scan_sessions_'.$timestamp.'.xlsx';
        if (Auth::user()) {
            $filename = 'scan_sessions'.Auth::user()->id.'_'.$timestamp.'.xlsx';
        }
        Excel::store(new ScanSessionExport($this->id), $filename);

        return $filename;
    }

    protected $appends = ['pairIds', 'client', 'user', 'paid'];

    public function getClientAttribute()
    {
        return $this->client()->first();
    }

    public function getPairIdsAttribute()
    {
        return $this->pairIds();
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }

    public function getPaidAttribute()
    {
        return $this->paid();
    }
}
