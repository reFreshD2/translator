<?php

declare(strict_types=1);

namespace app\Translation\Domain\Enum;

enum TokenType: string
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

    /**
     * @return TokenType[]
     */
    public static function sorted(): array
    {
        return [
            self::KEYWORD,
            self::ASSIGMENT,
            self::COMPARE,
            self::BRACKET,
            self::PLUS_OPERATOR,
            self::MULTIPLY_OPERATOR,
            self::INT,
            self::REAL,
            self::CHAR,
            self::STRING,
            self::SEPARATOR,
            self::ID,
        ];
    }
}
