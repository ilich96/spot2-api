<?php

namespace App\Services;

use App\Repositories\LandUseRepositoryInterface;

use function igorw\get_in;

class SquareMeterService implements SquareMeterServiceInterface
{
    public const CADASTRAL_COLONY_TYPES = [
        'A',
        'C',
        'E',
    ];

    public const AGGREGATE_TYPES = [
        'avg',
        'max',
        'min',
    ];

    public const AVG_AGGREGATE_TYPE_VALUE = 'avg';

    public const MAX_AGGREGATE_TYPE_VALUE = 'max';

    public const MIN_AGGREGATE_TYPE_VALUE = 'min';

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
     * @param string $zipCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getAveragePriceByZipCodeAndCadastralColonyType(
        string $zipCode,
        string $cadastralColonyType,
    ): ?array {
        if (!in_array($cadastralColonyType, self::CADASTRAL_COLONY_TYPES)) {
            return null;
        }

        $landUses = $this->landUseRepository->getAllByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );
        if (count($landUses) === 0) {
            return null;
        }

        $totalUnitPrice = 0;
        $totalUnitPriceConstruction = 0;
        foreach ($landUses as $landUse) {
            $unitPrices = $this->landUseService->getUnitPrices($landUse);
            if (is_null($unitPrices)) {
                var_dump("is_null(unitPrices)");
                continue;
            }

            var_dump("is_not_null(unitPrices)");

            $unitPrice = get_in($unitPrices, ['unitPrice']);
            $unitPriceConstruction = get_in($unitPrices, ['unitPriceConstruction']);
            if (!is_float($unitPrice) || !is_float($unitPriceConstruction)) {
                continue;
            }

            $totalUnitPrice += $unitPrice;
            $totalUnitPriceConstruction += $unitPriceConstruction;
        }

        return [
            'unitPrice' => $totalUnitPrice / count($landUses),
            'unitPriceConstruction' => $totalUnitPriceConstruction / count($landUses),
            'landUsesQuantity' => count($landUses),
        ];
    }

    /**
     * @param string $zipCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getMaximumPriceByZipCodeAndCadastralColonyType(
        string $zipCode,
        string $cadastralColonyType,
    ): ?array {
        if (!in_array($cadastralColonyType, self::CADASTRAL_COLONY_TYPES)) {
            return null;
        }

        $landUses = $this->landUseRepository->getAllByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );
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
            'unitPrice' => $maximumUnitPrice,
            'unitPriceConstruction' => $maximumUnitPriceConstruction,
            'landUsesQuantity' => count($landUses),
        ];
    }

    /**
     * @param string $zipCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getMinimumPriceByZipCodeAndCadastralColonyType(
        string $zipCode,
        string $cadastralColonyType,
    ): ?array {
        if (!in_array($cadastralColonyType, self::CADASTRAL_COLONY_TYPES)) {
            return null;
        }

        $landUses = $this->landUseRepository->getAllByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );
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
            'unitPrice' => $minimumUnitPrice,
            'unitPriceConstruction' => $minimumUnitPriceConstruction,
            'landUsesQuantity' => count($landUses),
        ];
    }
}
