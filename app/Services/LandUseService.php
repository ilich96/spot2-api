<?php

namespace App\Services;

use App\Models\LandUse;

class LandUseService implements LandUseServiceInterface
{
    /**
     * @param LandUse $landUse
     * @return array|null
     */
    public function getUnitPrices(LandUse $landUse): ?array
    {
        $landPrice = $landUse->land_price;
        $groundArea = $landUse->ground_area;
        $constructionArea = $landUse->construction_area;
        $subsidy = $landUse->subsidy;

        if (
            is_double($landPrice)
            && is_double($groundArea)
            && is_double($constructionArea)
            && is_double($subsidy)
        ) {
            $unitPrice = ($landPrice / $groundArea) - $subsidy;
            $unitPriceConstruction = ($landPrice / $constructionArea) - $subsidy;

            return [
                'unitPrice' => $unitPrice,
                'unitPriceConstruction' => $unitPriceConstruction,
            ];
        }

        return null;
    }
}
