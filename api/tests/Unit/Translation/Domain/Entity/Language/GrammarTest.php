<?php

declare(strict_types=1);

namespace Tests\Unit\Translation\Domain\Entity\Language;

use app\Translation\Domain\Entity\Language\Grammar;
use app\Translation\Domain\Enum\TokenType;
use app\Translation\Domain\Exception\UnsupportedTokenException;
use app\Translation\Domain\ValueObject\Grammar\GrammarRule;
use app\Translation\Domain\ValueObject\Grammar\RegexValue;
use app\Translation\Domain\ValueObject\Grammar\StringValue;
use Codeception\Test\Unit;
use DomainException;
use Generator;

class GrammarTest extends Unit
{
    private const CODE = 'var a,b:integer;\nbegin\na:=5;\nb:=10;\na+=b-15;\nend.';
    private Grammar $grammar;

    protected function _setUp(): void
    {
        $this->grammar = new Grammar([
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
    }

    /**
     * @dataProvider preprocessByTokenDataProvider
     */
    public function testPreprocessByTokenType(TokenType $tokenType, string $expectedCode): void
    {
        $actual = $this->grammar->preprocessByRule($tokenType, self::CODE);
        $this->assertEquals($expectedCode, $actual);
    }

    public static function preprocessByTokenDataProvider(): Generator
    {
        yield 'separator' => [
            TokenType::SEPARATOR,
            'var a , b : integer ; \nbegin\na:=5 ; \nb:=10 ; \na+=b-15 ; \nend . ',
        ];

        yield 'assigment' => [
            TokenType::ASSIGMENT,
            'var a,b:integer;\nbegin\na := 5;\nb := 10;\na += b-15;\nend.',
        ];

        yield 'plus' => [
            TokenType::PLUS_OPERATOR,
            'var a,b:integer;\nbegin\na:=5;\nb:=10;\na+=b - 15;\nend.',
        ];
    }

    /**
     * @dataProvider detectTokenTypeDataProvider
     */
    public function testDetectTokenType(string $value, TokenType $expectedTokenType): void
    {
        $this->assertEquals($expectedTokenType, $this->grammar->detectTokenType($value));
    }

    public static function detectTokenTypeDataProvider(): Generator
    {
        yield 'string' => ["'hello'", TokenType::STRING];
        yield 'char' => ["'a'", TokenType::CHAR];
        yield 'compare' => ['<>', TokenType::COMPARE];
        yield 'plus' => ['+', TokenType::PLUS_OPERATOR];
        yield 'multiply' => ['*', TokenType::MULTIPLY_OPERATOR];
        yield 'assigment' => [':=', TokenType::ASSIGMENT];
        yield 'int' => ['9', TokenType::INT];
        yield 'real' => ['9.1', TokenType::REAL];
        yield 'keyword' => ['begin', TokenType::KEYWORD];
        yield 'id' => ['myvar', TokenType::ID];
        yield 'separator' => [';', TokenType::SEPARATOR];
        yield 'bracket' => ['(', TokenType::BRACKET];
    }

    public function testUnexpectedTokenTypeException(): void
    {
        $this->expectException(UnsupportedTokenException::class);
        $this->expectExceptionMessage('Unsupported token type');
        $this->grammar->detectTokenType('$');
    }

    public function testNotFullGrammarException(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Not full grammar');
        new Grammar([]);
    }
}
