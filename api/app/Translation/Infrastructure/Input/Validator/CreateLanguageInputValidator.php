<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Input\Validator;

use app\Translation\Domain\Enum\GrammarRuleType;
use app\Translation\Domain\Enum\TokenType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class CreateLanguageInputValidator implements InputValidatorInterface
{

    public function validate(array $data): bool
    {
        $validator = Validator::make($data, [
            'language' => 'string|required',
            'rules' => 'required|array',
            'rules.*.tokenType' => [new Enum(TokenType::class)],
            'rules.*.type' => [new Enum(GrammarRuleType::class)],
            'rules.*.rule' => 'required|string'
        ]);

        try {
            $validator->validate();
            return true;
        } catch (ValidationException) {
            return false;
        }
    }
}
