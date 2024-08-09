<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface LandUseRepositoryInterface
{
    /**
     * @param string $zipCode
     * @param string $cadastralColonyType
     * @return Collection
     */
    public function getAllByZipCodeAndCadastralColonyType(
        string $zipCode,
        string $cadastralColonyType,
    ): Collection;
}
