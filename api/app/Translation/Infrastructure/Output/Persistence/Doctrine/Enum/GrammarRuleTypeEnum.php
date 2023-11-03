<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Output\Persistence\Doctrine\Enum;

enum GrammarRuleTypeEnum: string
{
    case STRING = 'string';
    case REGEX = 'regex';
}
