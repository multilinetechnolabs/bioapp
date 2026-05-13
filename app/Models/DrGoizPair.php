<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;

class DrGoizPair extends Base
{
    use ValidatingTrait;

    protected $table = 'dr_goiz_pairs';

    protected $fillable = [
        'place',
        'place_es',
        'resonance',
        'resonance_es',
        'name',
        'name_es',
        'characteristic',
        'characteristic_es',
        'description',
        'description_es',
    ];

    protected $rules = [
        'name' => 'nullable|string',
    ];
}
