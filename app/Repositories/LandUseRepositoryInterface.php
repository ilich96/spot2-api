<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface LandUseRepositoryInterface
{
    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @return Collection
     */
    public function getAllByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
    ): Collection;
}
