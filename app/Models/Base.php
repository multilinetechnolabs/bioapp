<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HelperMethods;

abstract class Base extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HelperMethods;

    use \OwenIt\Auditing\Auditable;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->append([
            'deletable',
            'editable'
        ]);
    }
}
