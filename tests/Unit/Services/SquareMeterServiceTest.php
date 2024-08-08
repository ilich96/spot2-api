<?php

namespace Services;

use App\Models\LandUse;
use App\Repositories\LandUseRepositoryInterface;
use App\Services\LandUseServiceInterface;
use App\Services\SquareMeterService;
use App\Services\SquareMeterServiceInterface;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SquareMeterServiceTest extends TestCase
{
    /**
     * @var SquareMeterServiceInterface|null $sut
     */
    private ?SquareMeterServiceInterface $sut;

    /**
     * @var LandUseRepositoryInterface|null $landUseRepository
     */
    private ?LandUseRepositoryInterface $landUseRepository;

    /**
     * @var LandUseServiceInterface|null
     */
    private ?LandUseServiceInterface $landUseService;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        $this->landUseRepository = Mockery::mock(
            LandUseRepositoryInterface::class
        );
        $this->landUseService = Mockery::mock(
            LandUseServiceInterface::class
        );

        $this->sut = new SquareMeterService(
            $this->landUseRepository,
            $this->landUseService,
        );

        parent::setUp();
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        $this->sut = null;
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @return array[]
     */
    public static function dataProvider(): array
    {
        $postalCode = '8000';
        $cadastralColonyType = 'A';
        $landUse = new LandUse();
        $landUseRepositoryResult = collect([
            $landUse,
            $landUse,
            $landUse,
            $landUse,
            $landUse,
        ]);

        $firstLandUseServiceResult = null;

        $secondLandUseServiceResult = [
            'unitPrice' => 7.67,
            'unitPriceConstruction' => 2.45,
        ];

        $thirdLandUseServiceResult = [
            'unitPrice' => 9.11,
            'unitPriceConstruction' => 56.6,
        ];

        $fourthLandUseServiceResult = [
            'unitPrice' => 4.86,
            'unitPriceConstruction' => 7.35,
        ];

        $fifthLandUseServiceResult = [];

        $expectedAverageUnitPrice = (7.67 + 9.11 + 4.86) / count($landUseRepositoryResult);
        $expectedAverageUnitPriceConstruction = (2.45 + 56.6 + 7.35) / count($landUseRepositoryResult);

        $expectedMaximumUnitPrice = 9.11;
        $expectedMaximumUnitPriceConstruction = 56.6;

        $expectedMinimumUnitPrice = 4.86;
        $expectedMinimumUnitPriceConstruction = 2.45;

        $expectedResults = [
            'expectedAverageUnitPrice' => $expectedAverageUnitPrice,
            'expectedAverageUnitPriceConstruction' => $expectedAverageUnitPriceConstruction,
            'expectedMaximumUnitPrice' => $expectedMaximumUnitPrice,
            'expectedMaximumUnitPriceConstruction' => $expectedMaximumUnitPriceConstruction,
            'expectedMinimumUnitPrice' => $expectedMinimumUnitPrice,
            'expectedMinimumUnitPriceConstruction' => $expectedMinimumUnitPriceConstruction,
        ];

        return [
            "Dataset for average, maximum and minimum functions" => [
                $postalCode,
                $cadastralColonyType,
                $landUse,
                $landUseRepositoryResult,
                $firstLandUseServiceResult,
                $secondLandUseServiceResult,
                $thirdLandUseServiceResult,
                $fourthLandUseServiceResult,
                $fifthLandUseServiceResult,
                $expectedResults,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public static function dataProviderForReturnNullResult(): array
    {
        return [
            "valid 'cadastralColonyType' value" => [
                'A',
                1,
            ],
            "invalid 'cadastralColonyType' value" => [
                'H',
                0,
            ],
        ];
    }

    /**
     * @param string $cadastralColonyType
     * @param int $landUseRepositoryInvokedTimes
     * @return void
     */
    #[DataProvider('dataProviderForReturnNullResult')]
    public function testGetAveragePriceByPostalCodeAndCadastralColonyTypeReturnsNull(
        string $cadastralColonyType,
        int $landUseRepositoryInvokedTimes,
    ): void {
        $postalCode = '8000';
        $this->landUseRepository->shouldReceive('getAllByPostalCodeAndCadastralColonyType')
            ->with($postalCode, $cadastralColonyType)
            ->times($landUseRepositoryInvokedTimes)
            ->andReturn(collect());

        $result = $this->sut->getAveragePriceByPostalCodeAndCadastralColonyType(
            $postalCode,
            $cadastralColonyType,
        );

        $this->assertNull($result);
    }

    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @param LandUse $landUse
     * @param Collection $landUseRepositoryResult
     * @param null $firstLandUseServiceResult
     * @param array $secondLandUseServiceResult
     * @param array $thirdLandUseServiceResult
     * @param array $fourthLandUseServiceResult
     * @param array $fifthLandUseServiceResult
     * @param array $expectedResults
     * @return void
     */
    #[DataProvider('dataProvider')] public function testGetAveragePriceByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
        LandUse $landUse,
        Collection $landUseRepositoryResult,
        null $firstLandUseServiceResult,
        array $secondLandUseServiceResult,
        array $thirdLandUseServiceResult,
        array $fourthLandUseServiceResult,
        array $fifthLandUseServiceResult,
        array $expectedResults,
    ): void {
        $this->landUseRepository->shouldReceive('getAllByPostalCodeAndCadastralColonyType')
            ->with($postalCode, $cadastralColonyType)
            ->times(1)
            ->andReturn($landUseRepositoryResult);

        $this->landUseService->shouldReceive('getUnitPrices')
            ->with($landUse)
            ->times(count($landUseRepositoryResult))
            ->andReturn(
                $firstLandUseServiceResult,
                $secondLandUseServiceResult,
                $thirdLandUseServiceResult,
                $fourthLandUseServiceResult,
                $fifthLandUseServiceResult,
            );

        $result = $this->sut->getAveragePriceByPostalCodeAndCadastralColonyType(
            $postalCode,
            $cadastralColonyType,
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('averageUnitPrice', $result);
        $this->assertArrayHasKey('averageUnitPriceConstruction', $result);
        $this->assertArrayHasKey('landUsesQuantity', $result);
        $this->assertEquals(
            $expectedResults['expectedAverageUnitPrice'],
            $result['averageUnitPrice']
        );
        $this->assertEquals(
            $expectedResults['expectedAverageUnitPriceConstruction'],
            $result['averageUnitPriceConstruction']
        );
        $this->assertEquals(count($landUseRepositoryResult), $result['landUsesQuantity']);
    }

    /**
     * @param string $cadastralColonyType
     * @param int $landUseRepositoryInvokedTimes
     * @return void
     */
    #[DataProvider('dataProviderForReturnNullResult')]
    public function testGetMaximumPriceByPostalCodeAndCadastralColonyTypeReturnsNull(
        string $cadastralColonyType,
        int $landUseRepositoryInvokedTimes,
    ): void {
        $postalCode = '8000';
        $this->landUseRepository->shouldReceive('getAllByPostalCodeAndCadastralColonyType')
            ->with($postalCode, $cadastralColonyType)
            ->times($landUseRepositoryInvokedTimes)
            ->andReturn(collect());

        $result = $this->sut->getMaximumPriceByPostalCodeAndCadastralColonyType(
            $postalCode,
            $cadastralColonyType,
        );

        $this->assertNull($result);
    }

    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @param LandUse $landUse
     * @param Collection $landUseRepositoryResult
     * @param null $firstLandUseServiceResult
     * @param array $secondLandUseServiceResult
     * @param array $thirdLandUseServiceResult
     * @param array $fourthLandUseServiceResult
     * @param array $fifthLandUseServiceResult
     * @param array $expectedResults
     * @return void
     */
    #[DataProvider('dataProvider')] public function testGetMaximumPriceByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
        LandUse $landUse,
        Collection $landUseRepositoryResult,
        null $firstLandUseServiceResult,
        array $secondLandUseServiceResult,
        array $thirdLandUseServiceResult,
        array $fourthLandUseServiceResult,
        array $fifthLandUseServiceResult,
        array $expectedResults,
    ): void {
        $this->landUseRepository->shouldReceive('getAllByPostalCodeAndCadastralColonyType')
            ->with($postalCode, $cadastralColonyType)
            ->times(1)
            ->andReturn($landUseRepositoryResult);

        $this->landUseService->shouldReceive('getUnitPrices')
            ->with($landUse)
            ->times(count($landUseRepositoryResult))
            ->andReturn(
                $firstLandUseServiceResult,
                $secondLandUseServiceResult,
                $thirdLandUseServiceResult,
                $fourthLandUseServiceResult,
                $fifthLandUseServiceResult,
            );

        $result = $this->sut->getMaximumPriceByPostalCodeAndCadastralColonyType(
            $postalCode,
            $cadastralColonyType,
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('maximumUnitPrice', $result);
        $this->assertArrayHasKey('maximumUnitPriceConstruction', $result);
        $this->assertArrayHasKey('landUsesQuantity', $result);
        $this->assertEquals(
            $expectedResults['expectedMaximumUnitPrice'],
            $result['maximumUnitPrice']
        );
        $this->assertEquals(
            $expectedResults['expectedMaximumUnitPriceConstruction'],
            $result['maximumUnitPriceConstruction']
        );
        $this->assertEquals(count($landUseRepositoryResult), $result['landUsesQuantity']);
    }

    /**
     * @param string $cadastralColonyType
     * @param int $landUseRepositoryInvokedTimes
     * @return void
     */
    #[DataProvider('dataProviderForReturnNullResult')]
    public function testGetMinimumPriceByPostalCodeAndCadastralColonyTypeReturnsNull(
        string $cadastralColonyType,
        int $landUseRepositoryInvokedTimes,
    ): void {
        $postalCode = '8000';
        $this->landUseRepository->shouldReceive('getAllByPostalCodeAndCadastralColonyType')
            ->with($postalCode, $cadastralColonyType)
            ->times($landUseRepositoryInvokedTimes)
            ->andReturn(collect());

        $result = $this->sut->getMinimumPriceByPostalCodeAndCadastralColonyType(
            $postalCode,
            $cadastralColonyType,
        );

        $this->assertNull($result);
    }

    /**
     * @param string $postalCode
     * @param string $cadastralColonyType
     * @param LandUse $landUse
     * @param Collection $landUseRepositoryResult
     * @param null $firstLandUseServiceResult
     * @param array $secondLandUseServiceResult
     * @param array $thirdLandUseServiceResult
     * @param array $fourthLandUseServiceResult
     * @param array $fifthLandUseServiceResult
     * @param array $expectedResults
     * @return void
     */
    #[DataProvider('dataProvider')] public function testGetMinimumPriceByPostalCodeAndCadastralColonyType(
        string $postalCode,
        string $cadastralColonyType,
        LandUse $landUse,
        Collection $landUseRepositoryResult,
        null $firstLandUseServiceResult,
        array $secondLandUseServiceResult,
        array $thirdLandUseServiceResult,
        array $fourthLandUseServiceResult,
        array $fifthLandUseServiceResult,
        array $expectedResults,
    ): void {
        $this->landUseRepository->shouldReceive('getAllByPostalCodeAndCadastralColonyType')
            ->with($postalCode, $cadastralColonyType)
            ->times(1)
            ->andReturn($landUseRepositoryResult);

        $this->landUseService->shouldReceive('getUnitPrices')
            ->with($landUse)
            ->times(count($landUseRepositoryResult))
            ->andReturn(
                $firstLandUseServiceResult,
                $secondLandUseServiceResult,
                $thirdLandUseServiceResult,
                $fourthLandUseServiceResult,
                $fifthLandUseServiceResult,
            );

        $result = $this->sut->getMinimumPriceByPostalCodeAndCadastralColonyType(
            $postalCode,
            $cadastralColonyType,
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('minimumUnitPrice', $result);
        $this->assertArrayHasKey('minimumUnitPriceConstruction', $result);
        $this->assertArrayHasKey('landUsesQuantity', $result);
        $this->assertEquals(
            $expectedResults['expectedMinimumUnitPrice'],
            $result['minimumUnitPrice']
        );
        $this->assertEquals(
            $expectedResults['expectedMinimumUnitPriceConstruction'],
            $result['minimumUnitPriceConstruction']
        );
        $this->assertEquals(count($landUseRepositoryResult), $result['landUsesQuantity']);
    }
}
