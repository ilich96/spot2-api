<?php

namespace App\Services;

interface SquareMeterServiceInterface
{
    /**
     * @param string $postalCode
     * @return array|null
     */
    public function getMaximumPriceByPostalCode(string $postalCode): ?array;
}
