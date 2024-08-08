<?php

namespace Services;

use App\Models\LandUse;
use App\Services\LandUseService;
use App\Services\LandUseServiceInterface;
use Mockery;
use Tests\TestCase;

class LandUseServiceTest extends TestCase
{
    /**
     * @var LandUseServiceInterface|null $sut
     */
    private ?LandUseServiceInterface $sut;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        $this->sut = new LandUseService();

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
    public function testGetUnitPricesWithEmptyLandUse(): void
    {
        $landUse = new LandUse();

        $result = $this->sut->getUnitPrices($landUse);

        $this->assertNull($result);
    }

    public function testGetUnitPrices(): void
    {
        $landUse = new LandUse();
        $landPrice = $landUse->land_price = 198.5;
        $groundArea = $landUse->ground_area = 68955.64;
        $constructionArea = $landUse->construction_area = 75004.44;
        $subsidy = $landUse->subsidy = 15.99;

        $unitPriceExpectedResult = ($landPrice / $groundArea) - $subsidy;
        $unitPriceConstructionExpectedResult = ($landPrice / $constructionArea) - $subsidy;

        $result = $this->sut->getUnitPrices($landUse);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('unitPrice', $result);
        $this->assertArrayHasKey('unitPriceConstruction', $result);
        $this->assertEquals($unitPriceExpectedResult, $result['unitPrice']);
        $this->assertEquals($unitPriceConstructionExpectedResult, $result['unitPriceConstruction']);
    }
}
