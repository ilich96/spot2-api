<?php

namespace App\Validators;

use App\Services\SquareMeterService;

class AggregateTypeValidator implements ValidatorInterface
{
    /**
     * @var string|null $errorMessage
     */
    protected ?string $errorMessage;

    public function isValid(mixed $data): bool
    {
        if (!is_string($data)) {
            $this->errorMessage = 'aggregateType should be a string.';

            return false;
        }

        if (!in_array($data, SquareMeterService::AGGREGATE_TYPES)) {
            $this->errorMessage = 'aggregateType has an invalid value.';

            return false;
        }

        return true;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
