<?php

namespace Tests\Unit\Validators;

use App\Validators\CadastralColonyTypeValidator;
use App\Validators\ValidatorInterface;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CadastralColonyTypeValidatorTest extends TestCase
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
        $this->sut = new CadastralColonyTypeValidator();

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
            "'cadastralColonyType' is a number" => [
                1,
                false,
            ],
            "'cadastralColonyType' is an array" => [
                [],
                false,
            ],
            "'cadastralColonyType is null" => [
                null,
                false,
            ],
            "'cadastralColonyType' has an invalid value" => [
                'H',
                false,
            ],
            "'cadastralColonyType' has a valid value" => [
                'C',
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
