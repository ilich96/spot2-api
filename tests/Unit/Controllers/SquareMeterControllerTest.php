<?php

namespace Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SquareMeterController;
use App\Services\SquareMeterServiceInterface;
use App\Validators\AggregateTypeValidator;
use App\Validators\CadastralColonyTypeValidator;
use App\Validators\ZipCodeValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SquareMeterControllerTest extends TestCase
{
    /**
     * @var Controller|null $sut
     */
    private ?Controller $sut;

    /**
     * @var ZipCodeValidator|null $zipCodeValidator
     */
    private ?ZipCodeValidator $zipCodeValidator;

    /**
     * @var AggregateTypeValidator|null $aggregateTypeValidator
     */
    private ?AggregateTypeValidator $aggregateTypeValidator;

    /**
     * @var CadastralColonyTypeValidator|null $cadastralColonyTypeValidator
     */
    private ?CadastralColonyTypeValidator $cadastralColonyTypeValidator;

    /**
     * @var SquareMeterServiceInterface|null $squareMeterService
     */
    private ?SquareMeterServiceInterface $squareMeterService;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->zipCodeValidator = Mockery::mock(
            ZipCodeValidator::class
        );
        $this->aggregateTypeValidator = Mockery::mock(
            AggregateTypeValidator::class
        );
        $this->cadastralColonyTypeValidator = Mockery::mock(
            CadastralColonyTypeValidator::class
        );
        $this->squareMeterService = Mockery::mock(
            SquareMeterServiceInterface::class
        );

        $this->sut = new SquareMeterController(
            $this->zipCodeValidator,
            $this->aggregateTypeValidator,
            $this->cadastralColonyTypeValidator,
            $this->squareMeterService,
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
        return [
            "'zipCodeValidator' returns false" => [
                1,
                false,
                1,
                0,
                false,
                0,
                0,
                0,
                false,
                0,
                0,
                null,
                0,
                null,
                0,
                null,
                '',
                400,
            ],
            "'aggregateTypeValidator' returns false" => [
                1,
                true,
                0,
                1,
                false,
                1,
                0,
                0,
                false,
                0,
                0,
                null,
                0,
                null,
                0,
                null,
                '',
                400,
            ],
            "'cadastralColonyTypeValidator' returns false" => [
                1,
                true,
                0,
                1,
                true,
                0,
                1,
                1,
                false,
                1,
                0,
                null,
                0,
                null,
                0,
                null,
                '',
                400,
            ],
            "'aggregateType' is 'avg'" => [
                1,
                true,
                0,
                1,
                true,
                0,
                1,
                1,
                true,
                0,
                1,
                null,
                0,
                null,
                0,
                null,
                'avg',
                400,
            ],
            "'aggregateType' is 'max'" => [
                1,
                true,
                0,
                1,
                true,
                0,
                1,
                1,
                true,
                0,
                0,
                null,
                1,
                null,
                0,
                null,
                'max',
                400,
            ],
            "'aggregateType' is 'min'" => [
                1,
                true,
                0,
                1,
                true,
                0,
                1,
                1,
                true,
                0,
                0,
                null,
                0,
                null,
                1,
                [],
                'min',
                200,
            ],
        ];
    }

    /**
     * @param int $zipCodeValidatorValidateInvokedTimes
     * @param bool $zipCodeValidatorValidateResult
     * @param int $zipCodeValidatorGetMessagesInvokedTimes
     * @param int $aggregateTypeValidatorValidateInvokedTimes
     * @param bool $aggregateTypeValidatorValidateResult
     * @param int $aggregateTypeValidatorGetMessagesInvokedTimes
     * @param int $requestQueryInvokedTimes
     * @param int $cadastralColonyTypeValidatorValidateInvokedTimes
     * @param bool $cadastralColonyTypeValidatorValidateResult
     * @param int $cadastralColonyTypeValidatorGetMessagesInvokedTimes
     * @param int $squareMeterGetAverageInvokedTimes
     * @param array|null $squareMeterGetAverageResult
     * @param int $squareMeterGetMaximumInvokedTimes
     * @param array|null $squareMeterGetMaximumResult
     * @param int $squareMeterGetMinimumInvokedTimes
     * @param array|null $squareMeterGetMinimumResult
     * @param string $aggregateType
     * @param int $expectedStatusCode
     * @return void
     */
    #[DataProvider('dataProvider')] public function testIndex(
        int $zipCodeValidatorValidateInvokedTimes,
        bool $zipCodeValidatorValidateResult,
        int $zipCodeValidatorGetMessagesInvokedTimes,
        int $aggregateTypeValidatorValidateInvokedTimes,
        bool $aggregateTypeValidatorValidateResult,
        int $aggregateTypeValidatorGetMessagesInvokedTimes,
        int $requestQueryInvokedTimes,
        int $cadastralColonyTypeValidatorValidateInvokedTimes,
        bool $cadastralColonyTypeValidatorValidateResult,
        int $cadastralColonyTypeValidatorGetMessagesInvokedTimes,
        int $squareMeterGetAverageInvokedTimes,
        ?array $squareMeterGetAverageResult,
        int $squareMeterGetMaximumInvokedTimes,
        ?array $squareMeterGetMaximumResult,
        int $squareMeterGetMinimumInvokedTimes,
        ?array $squareMeterGetMinimumResult,
        string $aggregateType,
        int $expectedStatusCode,
    ): void {
        $this->zipCodeValidator->shouldReceive('isValid')
            ->times($zipCodeValidatorValidateInvokedTimes)
            ->andReturn($zipCodeValidatorValidateResult);
        $this->zipCodeValidator->shouldReceive('getErrorMessage')
            ->times($zipCodeValidatorGetMessagesInvokedTimes);

        $this->aggregateTypeValidator->shouldReceive('isValid')
            ->times($aggregateTypeValidatorValidateInvokedTimes)
            ->andReturn($aggregateTypeValidatorValidateResult);
        $this->aggregateTypeValidator->shouldReceive('getErrorMessage')
            ->times($aggregateTypeValidatorGetMessagesInvokedTimes);

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('query')
            ->times($requestQueryInvokedTimes)
            ->andReturn($aggregateType);

        $this->cadastralColonyTypeValidator->shouldReceive('isValid')
            ->times($cadastralColonyTypeValidatorValidateInvokedTimes)
            ->andReturn($cadastralColonyTypeValidatorValidateResult);
        $this->cadastralColonyTypeValidator->shouldReceive('getErrorMessage')
            ->times($cadastralColonyTypeValidatorGetMessagesInvokedTimes);

        $this->squareMeterService->shouldReceive('getAveragePriceByZipCodeAndCadastralColonyType')
            ->times($squareMeterGetAverageInvokedTimes)
            ->andReturn($squareMeterGetAverageResult);

        $this->squareMeterService->shouldReceive('getMaximumPriceByZipCodeAndCadastralColonyType')
            ->times($squareMeterGetMaximumInvokedTimes)
            ->andReturn($squareMeterGetMaximumResult);

        $this->squareMeterService->shouldReceive('getMinimumPriceByZipCodeAndCadastralColonyType')
            ->times($squareMeterGetMinimumInvokedTimes)
            ->andReturn($squareMeterGetMinimumResult);

        $result = $this->sut->index(
            $request,
            '12345',
            $aggregateType,
        );

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expectedStatusCode, $result->getStatusCode());
    }
}
