<?php

namespace App\Http\Controllers;

use App\Services\SquareMeterService;
use App\Services\SquareMeterServiceInterface;
use App\Validators\AggregateTypeValidator;
use App\Validators\CadastralColonyTypeValidator;
use App\Validators\PostalCodeValidator;
use Illuminate\Http\Request;

use function igorw\get_in;

class SquareMeterController extends Controller
{
    /**
     * @param PostalCodeValidator $postalCodeValidator
     * @param AggregateTypeValidator $aggregateTypeValidator
     * @param CadastralColonyTypeValidator $cadastralColonyTypeValidator
     * @param SquareMeterServiceInterface $squareMeterService
     */
    public function __construct(
        protected PostalCodeValidator $postalCodeValidator,
        protected AggregateTypeValidator $aggregateTypeValidator,
        protected CadastralColonyTypeValidator $cadastralColonyTypeValidator,
        protected SquareMeterServiceInterface $squareMeterService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(
        Request $request,
        string $postalCode,
        string $aggregateType
    ) {
        if (!$this->postalCodeValidator->isValid($postalCode)) {
            return response()->json([
                'statusCode' => 400,
                'details' => $this->postalCodeValidator->getErrorMessage(),
            ], 400);
        }

        if (!$this->aggregateTypeValidator->isValid($aggregateType)) {
            return response()->json([
                'statusCode' => 400,
                'details' => $this->aggregateTypeValidator->getErrorMessage(),
            ], 400);
        }

        $cadastralColonyType = $request->query('cve_vus');
        if (!$this->cadastralColonyTypeValidator->isValid($cadastralColonyType)) {
            return response()->json([
                'statusCode' => 400,
                'details' => $this->cadastralColonyTypeValidator->getErrorMessage(),
            ], 400);
        }

        $prices = null;
        if ($aggregateType === SquareMeterService::AVG_AGGREGATE_TYPE_VALUE) {
            $prices = $this->squareMeterService->getAveragePriceByPostalCodeAndCadastralColonyType(
                $postalCode,
                $cadastralColonyType,
            );
        } elseif ($aggregateType === SquareMeterService::MAX_AGGREGATE_TYPE_VALUE) {
            $prices = $this->squareMeterService->getMaximumPriceByPostalCodeAndCadastralColonyType(
                $postalCode,
                $cadastralColonyType,
            );
        } elseif ($aggregateType === SquareMeterService::MIN_AGGREGATE_TYPE_VALUE) {
            $prices = $this->squareMeterService->getMinimumPriceByPostalCodeAndCadastralColonyType(
                $postalCode,
                $cadastralColonyType,
            );
        }

        if (is_null($prices)) {
            return response()->json([
                'statusCode' => 400,
                'details' => "Invalid request parameters.",
            ], 400);
        }

        $result = [
            'status' => true,
            'payload' => [
                'type' => $aggregateType,
                'price_unit' => get_in($prices, ['unitPrice']),
                'price_unit_construction' => get_in($prices, ['unitPriceConstruction']),
                'elements' => get_in($prices, ['landUsesQuantity']),
            ],
        ];

        return response()->json($result);
    }
}
