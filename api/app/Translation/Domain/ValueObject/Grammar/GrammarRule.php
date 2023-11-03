<?php

declare(strict_types=1);

namespace app\Translation\Domain\ValueObject\Grammar;

readonly class GrammarRule
{
    public function __construct(private StringableValueInterface $rule)
    {
    }

    public function preprocess(string $code): string
    {
        return $this->rule->format($code);
    }

    public function isSatisfy(string $word): bool
    {
        return $this->rule->equals($word);
    }
}
