<?php

namespace App\Models;

class ClientPair extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'pair_id'
    ];

    public static function rules($id = null)
    {
        return [
            'client_id' => 'required|integer',
            'pair_id' => 'required|integer'
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function pair()
    {
        return $this->belongsTo(Pair::class);
    }

    protected $appends = ['client', 'pair'];

    public function getClientAttribute()
    {
        return $this->client()->first();
    }

    public function getPairAttribute()
    {
        return $this->pair()->first();
    }
}
