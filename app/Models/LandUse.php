<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandUse extends Model
{
    protected $casts = [
        'land_price' => 'float',
        'ground_area' => 'float',
        'construction_area' => 'float',
        'subsidy' => 'float',
    ];
}
