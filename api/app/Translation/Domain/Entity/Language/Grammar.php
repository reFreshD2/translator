<?php

declare(strict_types=1);

namespace app\Translation\Domain\Entity\Language;

use app\Translation\Domain\Enum\TokenType;
use app\Translation\Domain\Exception\UnsupportedTokenException;
use app\Translation\Domain\ValueObject\Grammar\GrammarRule;
use DomainException;

readonly class Grammar
{
    /**
     * @param array<string,GrammarRule[]> $ruleSet
     */
    public function __construct(
        private array $ruleSet,
    ) {
        foreach (TokenType::cases() as $tokenType) {
            if (!isset($this->ruleSet[$tokenType->value]) || empty($this->ruleSet[$tokenType->value])) {
                throw new DomainException('Not full grammar');
            }
        }
    }

    public function preprocessByRule(TokenType $tokenType, string $code): string
    {
        if (!isset($this->ruleSet[$tokenType->value])) {
            throw new DomainException('Unsupported token type');
        }

        $processedCode = $code;
        foreach ($this->ruleSet[$tokenType->value] as $rule) {
            $processedCode = $rule->preprocess($processedCode);
        }

        return $processedCode;
    }

    /**
     * @throws UnsupportedTokenException
     */
    public function detectTokenType(string $word): TokenType
    {
        foreach (TokenType::cases() as $tokenType) {
            foreach ($this->ruleSet[$tokenType->value] as $rule) {
                if ($rule->isSatisfy($word)) {
                    return $tokenType;
                }
            }
        }

        throw new UnsupportedTokenException('Unsupported token type');
    }
}
