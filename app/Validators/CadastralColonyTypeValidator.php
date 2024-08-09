<?php

namespace App\Validators;

use App\Services\SquareMeterService;

class CadastralColonyTypeValidator implements ValidatorInterface
{
    /**
     * @var string|null $errorMessage
     */
    protected ?string $errorMessage;

    public function isValid(mixed $data): bool
    {
        if (!is_string($data)) {
            $this->errorMessage = 'cadastralColonyType should be a string.';

            return false;
        }

        if (!in_array($data, SquareMeterService::CADASTRAL_COLONY_TYPES)) {
            $this->errorMessage = 'cadastralColonyType has an invalid value.';

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
