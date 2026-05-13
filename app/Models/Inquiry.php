<?php

namespace App\Models;

class Inquiry extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone_no', 'mode'
    ];

    public static function rules($id = null)
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string',
            'mode' => 'required|string',
            'phone_no' => 'string'
        ];
    }
}
