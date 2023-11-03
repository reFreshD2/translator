<?php

declare(strict_types=1);

namespace app\Translation\Domain\Entity\Language;

use app\Translation\Domain\Enum\TokenType;
use app\Translation\Domain\Exception\UnsupportedTokenException;
use app\Translation\Domain\ValueObject\Position;
use app\Translation\Domain\ValueObject\Token;

class Language
{
    public function __construct(
        private string $name,
        private readonly Grammar $grammar,
    ) {
    }

    /**
     * @return Token[]
     * @throws UnsupportedTokenException
     */
    public function applyGrammar(string $code): array
    {
        $input = $this->preprocessCode($code);
        $tokens = [];
        $lines = explode(PHP_EOL, $input);

        foreach ($lines as $lineNumber => $line) {
            if (empty($line)) {
                continue;
            }

            $words = explode(' ', $line);
            $columnsNumber = 0;
            foreach ($words as $word) {
                if (empty($word)) {
                    continue;
                }

                try {
                    $tokens[] = new Token(
                        $this->grammar->detectTokenType($word),
                        $word,
                        new Position($lineNumber + 1, $columnsNumber),
                    );
                } catch (UnsupportedTokenException $exception) {
                    throw $exception->withLinePosition($lineNumber + 1)
                        ->withColumnPosition($columnsNumber)
                        ->withValue($word);
                }

                $columnsNumber += strlen($word) + 1;
            }
        }

        return $tokens;
    }

    private function preprocessCode(string $code): string
    {
        $formattedTokenTypes = [
            TokenType::SEPARATOR,
            TokenType::ASSIGMENT,
            TokenType::BRACKET,
            TokenType::COMPARE,
            TokenType::PLUS_OPERATOR,
            TokenType::MULTIPLY_OPERATOR,
        ];

        foreach ($formattedTokenTypes as $formattedTokenType) {
            $code = $this->grammar->preprocessByRule($formattedTokenType, $code);
        }

        return preg_replace('/ +/', ' ', $code);
    }
}
