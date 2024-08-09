<?php

namespace Tests\Integration;

use App\Repositories\LandUseRepository;
use App\Services\LandUseService;
use App\Services\SquareMeterService;
use Database\Seeders\LandUseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SquareMeterServiceTest extends TestCase
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
            [
                '01120',
                'A',
            ],
            [
                '01740',
                'C',
            ],
            [
                '01408',
                'E',
            ],
        ];
    }

    /**
     * @param string $zipCode
     * @param string $areaColonyType
     * @return void
     */
    #[DataProvider('dataProvider')] public function testAveragePrice(
        string $zipCode,
        string $areaColonyType,
    ): void {
        $landUseRepository = new LandUseRepository();
        $landUseService = new LandUseService();

        $squareMeterService = new SquareMeterService(
            $landUseRepository,
            $landUseService,
        );

        $result = $squareMeterService->getAveragePriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $areaColonyType,
        );

        $this->assertIsArray($result);
    }

    /**
     * @param string $zipCode
     * @param string $areaColonyType
     * @return void
     */
    #[DataProvider('dataProvider')] public function testMaximumPrice(
        string $zipCode,
        string $areaColonyType,
    ): void {
        $landUseRepository = new LandUseRepository();
        $landUseService = new LandUseService();

        $squareMeterService = new SquareMeterService(
            $landUseRepository,
            $landUseService,
        );

        $result = $squareMeterService->getMaximumPriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $areaColonyType,
        );

        $this->assertIsArray($result);
    }

    /**
     * @param string $zipCode
     * @param string $areaColonyType
     * @return void
     */
    #[DataProvider('dataProvider')] public function testMinimumPrice(
        string $zipCode,
        string $areaColonyType,
    ): void {
        $landUseRepository = new LandUseRepository();
        $landUseService = new LandUseService();

        $squareMeterService = new SquareMeterService(
            $landUseRepository,
            $landUseService,
        );

        $result = $squareMeterService->getMinimumPriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $areaColonyType,
        );

        $this->assertIsArray($result);
    }
}
