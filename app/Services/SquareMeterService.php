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
    public function getAveragePriceByPostalCode(string $postalCode): ?array
    {
        $landUses = $this->landUseRepository->getAllByPostalCode($postalCode);
        if (count($landUses) === 0) {
            return null;
        }

        $totalUnitPrice = 0;
        $totalUnitPriceConstruction = 0;
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

            $totalUnitPrice += $unitPrice;
            $totalUnitPriceConstruction += $unitPriceConstruction;
        }

        return [
            'averageUnitPrice' => $totalUnitPrice / count($landUses),
            'averageUnitPriceConstruction' => $totalUnitPriceConstruction / count($landUses),
            'landUsesQuantity' => count($landUses),
        ];
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

    /**
     * @param string $postalCode
     * @return array|null
     */
    public function getMinimumPriceByPostalCode(string $postalCode): ?array
    {
        $landUses = $this->landUseRepository->getAllByPostalCode($postalCode);
        if (count($landUses) === 0) {
            return null;
        }

        $minimumUnitPrice = PHP_FLOAT_MAX;
        $minimumUnitPriceConstruction = PHP_FLOAT_MAX;
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

            if ($unitPrice < $minimumUnitPrice) {
                $minimumUnitPrice = $unitPrice;
            }

            if ($unitPriceConstruction < $minimumUnitPriceConstruction) {
                $minimumUnitPriceConstruction = $unitPriceConstruction;
            }
        }

        return [
            'minimumUnitPrice' => $minimumUnitPrice,
            'minimumUnitPriceConstruction' => $minimumUnitPriceConstruction,
            'landUsesQuantity' => count($landUses),
        ];
    }
}
