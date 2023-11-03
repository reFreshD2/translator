<?php

declare(strict_types=1);

namespace app\Translation\Domain\ValueObject\Grammar;

use DomainException;

readonly class RegexValue implements StringableValueInterface
{
    public function __construct(private string $value)
    {
    }

    public function equals(string $value): bool
    {
        $equals = preg_match($this->value, $value);
        if ($equals === false) {
            throw new DomainException('Unsupported regex');
        }

        return (bool)$equals;
    }

    public function format(string $subject): string
    {
        return preg_replace_callback(
            $this->value,
            static fn(array $matches): string => ' ' . $matches[0] . ' ',
            $subject,
        );
    }
}
