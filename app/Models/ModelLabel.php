<?php

namespace App\Models;

class ModelLabel extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'target', 'pair_id', 'label', 'point_x', 'point_y', 'point_z', 'label_x', 'label_y', 'label_z',
        'scan_type'
    ];

    public static function rules($id = null)
    {
        return [
            'target' => 'required|string',
            'pair_id' => 'required|integer|exists:pairs,id',
            'label' => 'nullable|string',
            'point_x' => 'required|numeric',
            'point_y' => 'required|numeric',
            'point_z' => 'required|numeric',
            'label_x' => 'nullable|numeric',
            'label_y' => 'nullable|numeric',
            'label_z' => 'nullable|numeric',
            'scan_type' => 'required|string'
        ];
    }

    public function pair()
    {
        return $this->belongsTo(Pair::class);
    }

    public function point()
    {
        return $this->pair();
    }

    protected $appends = ['point'];

    public function getPointAttribute()
    {
        return $this->point()->first();
    }
}
