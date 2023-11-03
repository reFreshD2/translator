<?php

declare(strict_types=1);

namespace app\Translation\Domain\ValueObject;

use app\Translation\Domain\Enum\TokenType;

final readonly class Token
{
    public function __construct(public TokenType $type, public string $value, public Position $position)
    {
    }

    public function __toString(): string
    {
        return "[{$this->type->value}, {$this->value}, {$this->position}";
    }
}
