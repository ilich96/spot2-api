<?php

namespace Tests\Unit\Validators;

use App\Validators\ZipCodeValidator;
use App\Validators\ValidatorInterface;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ZipCodeValidatorTest extends TestCase
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
        $this->sut = new ZipCodeValidator();

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
            "'postalCode' is a number" => [
                1,
                false,
            ],
            "'postalCode' is an array" => [
                [],
                false,
            ],
            "'postalCode is null" => [
                null,
                false,
            ],
            "'postalCode' is a string" => [
                '012345',
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
