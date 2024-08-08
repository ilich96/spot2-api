<?php

namespace App\Repositories;

interface LandUseRepositoryInterface
{
    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @return array
     */
    public function getAllByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
    ): array;
}
