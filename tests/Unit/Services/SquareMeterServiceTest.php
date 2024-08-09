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
        $zipCode = '8000';
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
                $zipCode,
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
    public function testGetAveragePriceByZipCodeAndCadastralColonyTypeReturnsNull(
        string $cadastralColonyType,
        int $landUseRepositoryInvokedTimes,
    ): void {
        $zipCode = '8000';
        $this->landUseRepository->shouldReceive('getAllByZipCodeAndCadastralColonyType')
            ->with($zipCode, $cadastralColonyType)
            ->times($landUseRepositoryInvokedTimes)
            ->andReturn(collect());

        $result = $this->sut->getAveragePriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );

        $this->assertNull($result);
    }

    /**
     * @param string $zipCode
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
    #[DataProvider('dataProvider')] public function testGetAveragePriceByZipCodeAndCadastralColonyType(
        string $zipCode,
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
        $this->landUseRepository->shouldReceive('getAllByZipCodeAndCadastralColonyType')
            ->with($zipCode, $cadastralColonyType)
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

        $result = $this->sut->getAveragePriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('unitPrice', $result);
        $this->assertArrayHasKey('unitPriceConstruction', $result);
        $this->assertArrayHasKey('landUsesQuantity', $result);
        $this->assertEquals(
            $expectedResults['expectedAverageUnitPrice'],
            $result['unitPrice']
        );
        $this->assertEquals(
            $expectedResults['expectedAverageUnitPriceConstruction'],
            $result['unitPriceConstruction']
        );
        $this->assertEquals(count($landUseRepositoryResult), $result['landUsesQuantity']);
    }

    /**
     * @param string $cadastralColonyType
     * @param int $landUseRepositoryInvokedTimes
     * @return void
     */
    #[DataProvider('dataProviderForReturnNullResult')]
    public function testGetMaximumPriceByZipCodeAndCadastralColonyTypeReturnsNull(
        string $cadastralColonyType,
        int $landUseRepositoryInvokedTimes,
    ): void {
        $zipCode = '8000';
        $this->landUseRepository->shouldReceive('getAllByZipCodeAndCadastralColonyType')
            ->with($zipCode, $cadastralColonyType)
            ->times($landUseRepositoryInvokedTimes)
            ->andReturn(collect());

        $result = $this->sut->getMaximumPriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );

        $this->assertNull($result);
    }

    /**
     * @param string $zipCode
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
    #[DataProvider('dataProvider')] public function testGetMaximumPriceByZipCodeAndCadastralColonyType(
        string $zipCode,
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
        $this->landUseRepository->shouldReceive('getAllByZipCodeAndCadastralColonyType')
            ->with($zipCode, $cadastralColonyType)
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

        $result = $this->sut->getMaximumPriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('unitPrice', $result);
        $this->assertArrayHasKey('unitPriceConstruction', $result);
        $this->assertArrayHasKey('landUsesQuantity', $result);
        $this->assertEquals(
            $expectedResults['expectedMaximumUnitPrice'],
            $result['unitPrice']
        );
        $this->assertEquals(
            $expectedResults['expectedMaximumUnitPriceConstruction'],
            $result['unitPriceConstruction']
        );
        $this->assertEquals(count($landUseRepositoryResult), $result['landUsesQuantity']);
    }

    /**
     * @param string $cadastralColonyType
     * @param int $landUseRepositoryInvokedTimes
     * @return void
     */
    #[DataProvider('dataProviderForReturnNullResult')]
    public function testGetMinimumPriceByZipCodeAndCadastralColonyTypeReturnsNull(
        string $cadastralColonyType,
        int $landUseRepositoryInvokedTimes,
    ): void {
        $zipCode = '8000';
        $this->landUseRepository->shouldReceive('getAllByZipCodeAndCadastralColonyType')
            ->with($zipCode, $cadastralColonyType)
            ->times($landUseRepositoryInvokedTimes)
            ->andReturn(collect());

        $result = $this->sut->getMinimumPriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );

        $this->assertNull($result);
    }

    /**
     * @param string $zipCode
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
    #[DataProvider('dataProvider')] public function testGetMinimumPriceByZipCodeAndCadastralColonyType(
        string $zipCode,
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
        $this->landUseRepository->shouldReceive('getAllByZipCodeAndCadastralColonyType')
            ->with($zipCode, $cadastralColonyType)
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

        $result = $this->sut->getMinimumPriceByZipCodeAndCadastralColonyType(
            $zipCode,
            $cadastralColonyType,
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('unitPrice', $result);
        $this->assertArrayHasKey('unitPriceConstruction', $result);
        $this->assertArrayHasKey('landUsesQuantity', $result);
        $this->assertEquals(
            $expectedResults['expectedMinimumUnitPrice'],
            $result['unitPrice']
        );
        $this->assertEquals(
            $expectedResults['expectedMinimumUnitPriceConstruction'],
            $result['unitPriceConstruction']
        );
        $this->assertEquals(count($landUseRepositoryResult), $result['landUsesQuantity']);
    }
}
