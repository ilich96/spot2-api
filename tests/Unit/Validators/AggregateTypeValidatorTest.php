<?php

namespace Tests\Unit\Validators;

use App\Validators\AggregateTypeValidator;
use App\Validators\ValidatorInterface;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AggregateTypeValidatorTest extends TestCase
{
    /**
     * @var ValidatorInterface|null $sut
     */
    private ?ValidatorInterface $sut;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->sut = new AggregateTypeValidator();

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
            "'aggregateType' is a number" => [
                1,
                false,
            ],
            "'aggregateType' is an array" => [
                [],
                false,
            ],
            "'aggregateType is null" => [
                null,
                false,
            ],
            "'aggregateType' has an invalid value" => [
                'invalid',
                false,
            ],
            "'aggregateType' has a valid value" => [
                'avg',
                true,
            ],
        ];
    }

    /**
     * @param $data
     * @param bool $expectedResult
     * @return void
     */
    #[DataProvider('dataProvider')] public function testIsValid(
        $data,
        bool $expectedResult,
    ): void {
        $result = $this->sut->isValid($data);

        $this->assertEquals($expectedResult, $result);
    }
}
