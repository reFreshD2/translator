<?php

declare(strict_types=1);

namespace app\Translation\Domain\ValueObject\Grammar;

interface StringableValueInterface
{
    public function equals(string $value): bool;
    public function format(string $subject): string;
}
