<?php

namespace App\Services;

interface SquareMeterServiceInterface
{
    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getAveragePriceByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
    ): ?array;

    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getMaximumPriceByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
    ): ?array;

    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getMinimumPriceByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
    ): ?array;
}
