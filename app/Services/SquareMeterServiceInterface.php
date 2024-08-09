<?php

namespace App\Services;

interface SquareMeterServiceInterface
{
    /**
     * @param string $zipCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getAveragePriceByZipCodeAndCadastralColonyType(
        string $zipCode,
        string $cadastralColonyType,
    ): ?array;

    /**
     * @param string $zipCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getMaximumPriceByZipCodeAndCadastralColonyType(
        string $zipCode,
        string $cadastralColonyType,
    ): ?array;

    /**
     * @param string $zipCode
     * @param string $cadastralColonyType
     * @return array|null
     */
    public function getMinimumPriceByZipCodeAndCadastralColonyType(
        string $zipCode,
        string $cadastralColonyType,
    ): ?array;
}
