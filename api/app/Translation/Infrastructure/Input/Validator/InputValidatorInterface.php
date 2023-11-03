<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Input\Validator;

interface InputValidatorInterface
{
    public function validate(array $data): bool;
}
