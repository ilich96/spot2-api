<?php

namespace App\Repositories;

use App\Models\LandUse;
use Illuminate\Support\Collection;

class LandUseRepository implements LandUseRepositoryInterface
{
    private const AREA_COLONY_TYPE_VALUE = 'A';
    private const CORRIDOR_COLONY_TYPE_VALUE = 'C';
    private const ENCLAVE_COLONY_TYPE_VALUE = 'E';

    /**
     * @param string $zipCode
     * @param string $cadastralColonyType
     * @return Collection
     */
    public function getAllByZipCodeAndCadastralColonyType(
        string $zipCode,
        string $cadastralColonyType,
    ): Collection {
        $areaColonyTypeValue = self::AREA_COLONY_TYPE_VALUE;
        $corridorColonyTypeValue = self::CORRIDOR_COLONY_TYPE_VALUE;
        $enclaveColonyTypeValue = self::ENCLAVE_COLONY_TYPE_VALUE;

        return match ($cadastralColonyType) {
            $areaColonyTypeValue =>
                LandUse::where('zip_code', $zipCode)->where('area_colony_type', $areaColonyTypeValue)->get(),
            $corridorColonyTypeValue =>
                LandUse::where('zip_code', $zipCode)->where('area_colony_type', $corridorColonyTypeValue)->get(),
            $enclaveColonyTypeValue =>
                LandUse::where('zip_code', $zipCode)->where('area_colony_type', $enclaveColonyTypeValue)->get(),
            default => collect(),
        };
    }
}
