<?php

namespace Services;

use App\Models\LandUse;
use App\Repositories\LandUseRepositoryInterface;
use App\Services\LandUseServiceInterface;
use App\Services\SquareMeterService;
use Mockery;
use Tests\TestCase;

class SquareMeterServiceTest extends TestCase
{
    /**
     * @var SquareMeterService|null $sut
     */
    private ?SquareMeterService $sut;

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
     * @return void
     */
    public function testGetMaximumPriceByPostalCodeWithEmptyLandUses(): void
    {
        $postalCode = '8000';
        $this->landUseRepository->shouldReceive('getAllByPostalCode')
            ->with($postalCode)
            ->times(1)
            ->andReturn([]);

        $result = $this->sut->getMaximumPriceByPostalCode($postalCode);

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testGetMaximumPriceByPostalCode(): void
    {
        $postalCode = '8000';
        $landUse = new LandUse();
        $landUseRepositoryResult = [
            $landUse,
            $landUse,
            $landUse,
            $landUse,
            $landUse,
        ];

        $this->landUseRepository->shouldReceive('getAllByPostalCode')
            ->with($postalCode)
            ->times(1)
            ->andReturn($landUseRepositoryResult);

        $firstLandUseServiceResult = null;
        $secondLandUseServiceResult = [
            'unitPrice' => 5.45,
            'unitPriceConstruction' => 9.54,
        ];
        $thirdLandUseServiceResult = [
            'unitPrice' => 1.45,
            'unitPriceConstruction' => 12.54,
        ];
        $fourthLandUseServiceResult = [
            'unitPrice' => 11.99,
            'unitPriceConstruction' => 1.35,
        ];
        $fifthLandUseServiceResult = [];

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

        $result = $this->sut->getMaximumPriceByPostalCode($postalCode);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('maximumUnitPrice', $result);
        $this->assertArrayHasKey('maximumUnitPriceConstruction', $result);
        $this->assertArrayHasKey('landUsesQuantity', $result);
        $this->assertEquals(11.99, $result['maximumUnitPrice']);
        $this->assertEquals(12.54, $result['maximumUnitPriceConstruction']);
        $this->assertEquals(count($landUseRepositoryResult), $result['landUsesQuantity']);
    }
}
