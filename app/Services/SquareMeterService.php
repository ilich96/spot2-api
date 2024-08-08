<?php

namespace App\Services;

use App\Repositories\LandUseRepositoryInterface;
use function igorw\get_in;

class SquareMeterService implements SquareMeterServiceInterface
{
    /**
     * @param LandUseRepositoryInterface $landUseRepository
     * @param LandUseServiceInterface $landUseService
     */
    public function __construct(
        protected LandUseRepositoryInterface $landUseRepository,
        protected LandUseServiceInterface $landUseService,
    ) {
    }

    /**
     * @param string $postalCode
     * @return array|null
     */
    public function getMaximumPriceByPostalCode(string $postalCode): ?array
    {
        $landUses = $this->landUseRepository->getAllByPostalCode($postalCode);
        if (count($landUses) === 0) {
            return null;
        }

        $maximumUnitPrice = 0;
        $maximumUnitPriceConstruction = 0;
        foreach ($landUses as $landUse) {
            $unitPrices = $this->landUseService->getUnitPrices($landUse);
            if (is_null($unitPrices)) {
                continue;
            }

            $unitPrice = get_in($unitPrices, ['unitPrice']);
            $unitPriceConstruction = get_in($unitPrices, ['unitPriceConstruction']);
            if (!is_float($unitPrice) || !is_float($unitPriceConstruction)) {
                continue;
            }

            if ($unitPrice > $maximumUnitPrice) {
                $maximumUnitPrice = $unitPrice;
            }

            if ($unitPriceConstruction > $maximumUnitPriceConstruction) {
                $maximumUnitPriceConstruction = $unitPriceConstruction;
            }
        }

        return [
            'maximumUnitPrice' => $maximumUnitPrice,
            'maximumUnitPriceConstruction' => $maximumUnitPriceConstruction,
            'landUsesQuantity' => count($landUses),
        ];
    }
}
