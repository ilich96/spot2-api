<?php

namespace App\Repositories;

use App\Models\LandUse;

class LandUseRepository implements LandUseRepositoryInterface
{
    private const AREA_COLONY_TYPE_VALUE = 'A';
    private const CORRIDOR_COLONY_TYPE_VALUE = 'C';
    private const ENCLAVE_COLONY_TYPE_VALUE = 'E';

    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @return array
     */
    public function getAllByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
    ): array {
        $areaColonyTypeValue = self::AREA_COLONY_TYPE_VALUE;
        $corridorColonyTypeValue = self::CORRIDOR_COLONY_TYPE_VALUE;
        $enclaveColonyTypeValue = self::ENCLAVE_COLONY_TYPE_VALUE;

        return match ($cadastralColonyType) {
            $areaColonyTypeValue =>
                LandUse::where('postalCode', $postalCode)->where('areaColonyType', $areaColonyTypeValue)->get(),
            $corridorColonyTypeValue =>
                LandUse::where('postalCode', $postalCode)->where('areaColonyType', $corridorColonyTypeValue)->get(),
            $enclaveColonyTypeValue =>
                LandUse::where('postalCode', $postalCode)->where('areaColonyType', $enclaveColonyTypeValue)->get(),
            default => [],
        };
    }
}
