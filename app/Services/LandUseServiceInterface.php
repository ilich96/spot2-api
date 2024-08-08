<?php

namespace App\Services;

use App\Models\LandUse;

interface LandUseServiceInterface
{
    /**
     * @param LandUse $landUse
     * @return array
     */
    public function getUnitPrices(LandUse $landUse): array;
}
