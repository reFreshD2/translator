<?php

declare(strict_types=1);

namespace app\Translation\Domain\Enum;

enum GrammarRuleType: string
{
    case STRING = 'string';
    case REGEX = 'regex';
}
