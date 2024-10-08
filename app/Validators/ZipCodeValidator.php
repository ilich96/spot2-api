<?php

namespace App\Validators;

class ZipCodeValidator implements ValidatorInterface
{
    /**
     * @var string|null $errorMessage
     */
    protected ?string $errorMessage;

    public function isValid(mixed $data): bool
    {
        if (!is_string($data)) {
            $this->errorMessage = 'zipCode should be a string.';

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
