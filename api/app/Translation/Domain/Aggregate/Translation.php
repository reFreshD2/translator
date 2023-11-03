<?php

declare(strict_types=1);

namespace app\Translation\Domain\Aggregate;

use app\Translation\Domain\Entity\Language\Language;
use app\Translation\Domain\Exception\UnsupportedTokenException;
use app\Translation\Domain\ValueObject\Token;

final readonly class Translation
{
    public function __construct(
        private Language $inputLanguage,
        private string $code,
    ) {
    }

    /**
     * @return Token[]
     * @throws UnsupportedTokenException
     */
    public function getTokens(): array
    {
        return $this->inputLanguage->applyGrammar($this->code);
    }
}
