<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Output\Persistence\Doctrine\Enum;

enum LanguageTokenTypeEnum: string
{
    case STRING = 'string';
    case CHAR = 'char';
    case BRACKET = 'bracket';
    case ASSIGMENT = 'assigment';
    case SEPARATOR = 'separator';
    case PLUS_OPERATOR = 'plus';
    case MULTIPLY_OPERATOR = 'multiply';
    case COMPARE = 'compare';
    case REAL = 'real';
    case INT = 'int';
    case KEYWORD = 'keyword';
    case ID = 'id';
}
