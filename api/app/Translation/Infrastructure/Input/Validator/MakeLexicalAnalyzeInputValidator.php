<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Input\Validator;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MakeLexicalAnalyzeInputValidator implements InputValidatorInterface
{
    public function validate(array $data): bool
    {
        $validator = Validator::make($data, [
            'input' => 'required|string',
            'language' => 'required|string',
        ]);

        try {
            $validator->validate();
            return true;
        } catch (ValidationException) {
            return false;
        }
    }
}
