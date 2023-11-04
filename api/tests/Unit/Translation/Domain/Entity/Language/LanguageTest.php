<?php

declare(strict_types=1);

namespace Tests\Unit\Translation\Domain\Entity\Language;

use app\Translation\Domain\Entity\Language\Grammar;
use app\Translation\Domain\Entity\Language\Language;
use app\Translation\Domain\Enum\TokenType;
use app\Translation\Domain\ValueObject\Grammar\GrammarRule;
use app\Translation\Domain\ValueObject\Grammar\RegexValue;
use app\Translation\Domain\ValueObject\Grammar\StringValue;
use app\Translation\Domain\ValueObject\Position;
use app\Translation\Domain\ValueObject\Token;
use Codeception\Test\Unit;
use Generator;

class LanguageTest extends Unit
{
    private Language $language;

    protected function _setUp(): void
    {
        $grammar = new Grammar([
            TokenType::STRING->value => [
                new GrammarRule(new RegexValue('/\'.{2,}\'/')),
            ],
            TokenType::CHAR->value => [
                new GrammarRule(new RegexValue('/\'.\'/')),
            ],
            TokenType::COMPARE->value => [
                new GrammarRule(new RegexValue('/<>|[<>]=?|(?<![:\-+*\/])=/')),
            ],
            TokenType::INT->value => [
                new GrammarRule(new RegexValue('/(?<!\.)\d+(?!\.)/')),
            ],
            TokenType::REAL->value => [
                new GrammarRule(new RegexValue('/\d+\.?\d*/')),
            ],
            TokenType::ID->value => [
                new GrammarRule(new RegexValue('/[a-zA-Zа-яА-Я_]+/')),
            ],
            TokenType::BRACKET->value => [
                new GrammarRule(new RegexValue('/[()]/')),
            ],
            TokenType::ASSIGMENT->value => [
                new GrammarRule(new RegexValue('/[+\-*\/:]=/')),
            ],
            TokenType::MULTIPLY_OPERATOR->value => [
                new GrammarRule(new RegexValue('/[*\/](?!=)/')),
            ],
            TokenType::PLUS_OPERATOR->value => [
                new GrammarRule(new RegexValue('/[+\-](?!=)/')),
            ],
            TokenType::SEPARATOR->value => [
                new GrammarRule(new RegexValue('/[;,.]|:(?!=)/')),
            ],
            TokenType::KEYWORD->value => [
                new GrammarRule(new StringValue('begin')),
                new GrammarRule(new StringValue('end')),
                new GrammarRule(new StringValue('for')),
                new GrammarRule(new StringValue('to')),
                new GrammarRule(new StringValue('var')),
                new GrammarRule(new StringValue('downto')),
                new GrammarRule(new StringValue('do')),
                new GrammarRule(new StringValue('while')),
                new GrammarRule(new StringValue('repeat')),
                new GrammarRule(new StringValue('until')),
                new GrammarRule(new StringValue('if')),
                new GrammarRule(new StringValue('then')),
                new GrammarRule(new StringValue('else')),
                new GrammarRule(new StringValue('case')),
                new GrammarRule(new StringValue('break')),
                new GrammarRule(new StringValue('real')),
                new GrammarRule(new StringValue('char')),
                new GrammarRule(new StringValue('string')),
                new GrammarRule(new StringValue('boolean')),
                new GrammarRule(new StringValue('abs')),
                new GrammarRule(new StringValue('sqr')),
                new GrammarRule(new StringValue('sqrt')),
                new GrammarRule(new StringValue('exp')),
                new GrammarRule(new StringValue('write')),
                new GrammarRule(new StringValue('writeln')),
                new GrammarRule(new StringValue('readln')),
                new GrammarRule(new StringValue('true')),
                new GrammarRule(new StringValue('false')),
                new GrammarRule(new StringValue('integer')),
            ],
        ]);

        $this->language = new Language('Pascal', $grammar);
    }

    /**
     * @param Token[] $expectedTokens
     * @dataProvider applyGrammarDataProvider
     */
    public function testApplyGrammar(string $code, array $expectedTokens): void
    {
        $this->assertEquals($expectedTokens, $this->language->applyGrammar($code));
    }


    public static function applyGrammarDataProvider(): Generator
    {
        yield 'pascal_code_1.pas' => [
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
    }
}
