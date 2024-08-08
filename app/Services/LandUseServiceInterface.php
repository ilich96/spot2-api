<?php

namespace App\Services;

use App\Models\LandUse;

interface LandUseServiceInterface
{
    /**
     * @param LandUse $landUse
     * @return array|null
     */
    public function getUnitPrices(LandUse $landUse): ?array;
}
