<?php

namespace Repositories;

use App\Models\LandUse;
use App\Repositories\LandUseRepository;
use App\Repositories\LandUseRepositoryInterface;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Tests\TestCase;

class LandUseRepositoryTest extends TestCase
{
    /**
     * @var LandUseRepositoryInterface|null $sut
     */
    private ?LandUseRepositoryInterface $sut;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        $this->sut = new LandUseRepository();

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

    public static function dataProvider(): array
    {
        return [
            "'cadastralColonyType' is 'A'" => [
                'A',
                1,
            ],
            "'cadastralColonyType' is 'C'" => [
                'C',
                1,
            ],
            "'cadastralColonyType' is 'E'" => [
                'E',
                1,
            ],
            "'cadastralColonyType' is invalid" => [
                'H',
                0,
            ],
        ];
    }

    /**
     * @param string $cadastralColonyType
     * @param int $landUseWhereInvokedTimes
     * @return void
     */
    #[PreserveGlobalState(false)]
    #[RunInSeparateProcess]
    #[DataProvider('dataProvider')]
    public function testGetAllByPostalCodeAndCadastralColonyType(
        string $cadastralColonyType,
        int $landUseWhereInvokedTimes,
    ): void {
        $postalCode = '8000';
        $landUse = Mockery::mock('alias:' . LandUse::class);
        $landUse->shouldReceive('where')
            ->times($landUseWhereInvokedTimes)
            ->andReturnSelf();

        $landUse->shouldReceive('where')
            ->times($landUseWhereInvokedTimes)
            ->andReturnSelf();

        $landUse->shouldReceive('get')
            ->times($landUseWhereInvokedTimes)
            ->andReturn([]);

        $result = $this->sut->getAllByPostalCodeAndCadastralColonyType(
            $postalCode,
            $cadastralColonyType,
        );

        $this->assertIsArray($result);
    }
}
