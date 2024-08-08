<?php

namespace App\Services;

use App\Models\LandUse;

class LandUseService implements LandUseServiceInterface
{
    /**
     * @param LandUse $landUse
     * @return array
     */
    public function getUnitPrices(LandUse $landUse): array
    {
        $landPrice = $landUse->landPrice;
        $constructionLandPrice = $landUse->constructionLandPrice;
        $groundArea = $landUse->groundArea;
        $subsidy = $landUse->subsidy;

        if (is_double($landPrice) && is_double($constructionLandPrice) && is_double($groundArea) && is_double($subsidy)) {
            $unitPrice = ($landPrice / $groundArea) - $subsidy;
            $unitPriceConstruction = ($constructionLandPrice / $groundArea) - $subsidy;

            return [
                'unitPrice' => $unitPrice,
                'unitPriceConstruction' => $unitPriceConstruction,
            ];
        }

        return [];
    }
}
