<?php

declare(strict_types=1);

namespace Tests\Unit\LexicalAnalyzer\Domain\Service;

use app\LexicalAnalyzer\Domain\Exception\UnsupportedTokenException;
use app\LexicalAnalyzer\Domain\Service\Analyzer;
use app\LexicalAnalyzer\Domain\Service\Formatter;
use app\LexicalAnalyzer\Domain\ValueObject\Position;
use app\LexicalAnalyzer\Domain\ValueObject\Token;
use app\Shared\Domain\Enum\Language;
use app\Shared\Domain\Repository\LanguageTokenRepositoryInterface;
use app\Shared\Domain\ValueObject\LanguageToken;
use app\Translation\Domain\Enum\TokenType;
use app\Translation\Domain\ValueObject\Grammar\RegexValue;
use app\Translation\Domain\ValueObject\Grammar\StringValue;
use Codeception\Test\Unit;
use Exception;
use Generator;

class AnalyzerTest extends Unit
{
    private Analyzer $analyzer;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $map = [
            TokenType::SEPARATOR->value => [
                new LanguageToken(TokenType::SEPARATOR, new RegexValue('/^[:;,.]$/')),
            ],
            TokenType::PLUS_OPERATOR->value => [
                new LanguageToken(TokenType::PLUS_OPERATOR, new RegexValue('/^[+\-]$/')),
            ],
            TokenType::MULTIPLY_OPERATOR->value => [
                new LanguageToken(TokenType::MULTIPLY_OPERATOR, new RegexValue('/^[*\/]$/')),
            ],
            TokenType::ASSIGMENT->value => [
                new LanguageToken(TokenType::ASSIGMENT, new RegexValue('/^[+\-*\/:]=$/')),
            ],
            TokenType::BRACKET->value => [
                new LanguageToken(TokenType::BRACKET, new RegexValue('/^[()]$/')),
            ],
            TokenType::STRING->value => [
                new LanguageToken(TokenType::STRING, new RegexValue('/^\'.{2,}\'$/')),
            ],
            TokenType::CHAR->value => [
                new LanguageToken(TokenType::CHAR, new RegexValue('/^\'.\'$/')),
            ],
            TokenType::COMPARE->value => [
                new LanguageToken(TokenType::COMPARE, new RegexValue('/^([<>]=?|=|<>)$/')),
            ],
            TokenType::INT->value => [
                new LanguageToken(TokenType::INT, new RegexValue('/^\d+$/')),
            ],
            TokenType::REAL->value => [
                new LanguageToken(TokenType::REAL, new RegexValue('/^\d+\.?\d*$/')),
            ],
            TokenType::ID->value => [
                new LanguageToken(TokenType::ID, new RegexValue('/^[a-zA-Zа-яА-Я_]+$/')),
            ],
            TokenType::KEYWORD->value => [
                new LanguageToken(TokenType::KEYWORD, new StringValue('begin')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('end')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('for')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('to')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('var')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('downto')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('do')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('while')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('repeat')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('until')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('if')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('then')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('else')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('case')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('break')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('real')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('char')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('string')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('boolean')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('abs')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('sqr')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('sqrt')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('exp')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('write')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('writeln')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('readln')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('true')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('false')),
                new LanguageToken(TokenType::KEYWORD, new StringValue('integer')),
            ],
        ];

        $tokenRepository = $this->makeEmpty(LanguageTokenRepositoryInterface::class);
        $tokenRepository->method('getTokensByType')
            ->willReturnMap(
                array_map(
                    static fn(TokenType $tokenType) => [Language::PASCAL, $tokenType, $map[$tokenType->value]],
                    TokenType::cases(),
                ),
            );

        $this->analyzer = new Analyzer($tokenRepository, new Formatter($tokenRepository));
    }

    /**
     * @dataProvider pascalCodeDataProvider
     * @param Token[] $tokens
     * @throws UnsupportedTokenException
     */
    public function testParse(string $input, array $tokens): void
    {
        $actual = $this->analyzer->parse($input, Language::PASCAL);
        $this->assertEquals($tokens, $actual);
    }

    public static function pascalCodeDataProvider(): Generator
    {
        yield 'pascal_code_1' => [
            file_get_contents(__DIR__ . '/resources/pascal_code_1.pas'),
            [
                new Token(TokenType::KEYWORD, 'var', new Position(1, 0)),
                new Token(TokenType::ID, 'a', new Position(1, 4)),
                new Token(TokenType::SEPARATOR, ',', new Position(1, 6)),
                new Token(TokenType::ID, 'b', new Position(1, 8)),
                new Token(TokenType::SEPARATOR, ':', new Position(1, 10)),
                new Token(TokenType::KEYWORD, 'integer', new Position(1, 12)),
                new Token(TokenType::SEPARATOR, ';', new Position(1, 20)),
                new Token(TokenType::KEYWORD, 'begin', new Position(2, 0)),
                new Token(TokenType::ID, 'a', new Position(3, 0)),
                new Token(TokenType::ASSIGMENT, ':=', new Position(3, 2)),
                new Token(TokenType::ID, 'b', new Position(3, 5)),
                new Token(TokenType::SEPARATOR, ';', new Position(3, 7)),
                new Token(TokenType::KEYWORD, 'end', new Position(4, 0)),
                new Token(TokenType::SEPARATOR, '.', new Position(4, 4)),
            ],
        ];

        yield 'pascal_code_2' => [

        ];

        yield 'pascal_code_3' => [

        ];

        yield 'pascal_code_4' => [

        ];
    }
}
