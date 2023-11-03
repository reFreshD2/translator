<?php

declare(strict_types=1);

namespace app\Translation\Domain\ValueObject\Grammar;

readonly class StringValue implements StringableValueInterface
{
    public function __construct(private string $value)
    {
    }

    public function equals(string $value): bool
    {
        return $this->value === $value;
    }

    public function format(string $subject): string
    {
        return str_replace($this->value, ' ' . $this->value . ' ', $subject);
    }
}
