<?php

namespace Providers;

use App\Repositories\LandUseRepositoryInterface;
use App\Services\LandUseServiceInterface;
use App\Services\SquareMeterServiceInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testRegister(): void
    {
        $landUseRepository = $this->app->get(LandUseRepositoryInterface::class);
        $landUseService = $this->app->get(LandUseServiceInterface::class);
        $squareMeterService = $this->app->get(SquareMeterServiceInterface::class);

        $this->assertInstanceOf(
            LandUseRepositoryInterface::class,
            $landUseRepository
        );
        $this->assertInstanceOf(
            LandUseServiceInterface::class,
            $landUseService
        );
        $this->assertInstanceOf(
            SquareMeterServiceInterface::class,
            $squareMeterService
        );
    }
}
