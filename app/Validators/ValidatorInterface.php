<?php

namespace App\Validators;

interface ValidatorInterface
{
    /**
     * @param mixed $data
     * @return bool
     */
    public function isValid(mixed $data): bool;

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string;
}
