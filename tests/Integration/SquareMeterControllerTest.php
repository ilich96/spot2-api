<?php

namespace Tests\Integration;

use Database\Seeders\LandUseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SquareMeterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string $seeder
     */
    protected string $seeder = LandUseSeeder::class;

    /**
     * @return array[]
     */
    public static function dataProvider(): array
    {
        return [
            "'aggregateType' is invalid" => [
                '01120',
                'invalidAggregate',
                'A',
                400,
            ],
            "'cadastralColonyType' is invalid" => [
                '01120',
                'avg',
                'invalidCadastralColonyType',
                400,
            ],
            "There are no records in the database with the 'zipCode' value" => [
                '12345',
                'avg',
                'A',
                400,
            ],
            "Get 'avg' prices" => [
                '01120',
                'avg',
                'A',
                200,
            ],
            "Get 'max' prices" => [
                '01408',
                'max',
                'C',
                200,
            ],
            "Get 'min' prices" => [
                '01740',
                'min',
                'E',
                200,
            ],
        ];
    }

    #[DataProvider('dataProvider')] public function testSquareMeterRequest(
        string $zipCode,
        string $aggregateType,
        string $areaColonyType,
        int $expectedStatusCode,
    ): void {
        $squareMeterUrl
            = '/api/price-m2/zip-codes/' . $zipCode . '/aggregate/' . $aggregateType . '?cve_vus=' . $areaColonyType;

        $response = $this->getJson($squareMeterUrl);

        $response->assertStatus($expectedStatusCode);
    }
}
