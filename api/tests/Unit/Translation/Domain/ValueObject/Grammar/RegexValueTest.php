<?php

namespace Tests\Unit\Translation\Domain\ValueObject\Grammar;

use app\Translation\Domain\ValueObject\Grammar\RegexValue;
use Codeception\Test\Unit;
use Generator;

class RegexValueTest extends Unit
{
    private const REGEX = '/[+\-](?!=)/';

    private RegexValue $regexValue;

    protected function _setUp(): void
    {
        $this->regexValue = new RegexValue(self::REGEX);
    }

    /**
     * @dataProvider equalsDataProvider
     */
    public function testEquals(string $valueToEquals, bool $expected): void
    {
        $this->assertEquals($expected, $this->regexValue->equals($valueToEquals));
    }

    public static function equalsDataProvider(): Generator
    {
        yield 'true (+)' => ['+', true];
        yield 'true (-)' => ['-', true];
        yield 'false (+=)' => ['+=', false];
        yield 'false (-=)' => ['-=', false];
    }

    /**
     * @dataProvider formatDataProvider
     */
    public function testFormat(string $valueToFormat, string $expectedFormattedString): void
    {
        $this->assertEquals($expectedFormattedString, $this->regexValue->format($valueToFormat));
    }

    public static function formatDataProvider(): Generator
    {
        yield 'both (+,-)' => ['b:=a+b-c;', 'b:=a + b - c;'];
        yield 'plus (+)' => ['b:=a+b;', 'b:=a + b;'];
        yield 'minus (-)' => ['b:=a-b;', 'b:=a - b;'];
        yield 'both with ignore (+,-)' => ['b-=a-b+c;', 'b-=a - b + c;'];
        yield 'minus with ignore (-)' => ['b-=a-b;', 'b-=a - b;'];
        yield 'plus with ignore (+)' => ['b+=a+b;', 'b+=a + b;'];
        yield 'no one' => ['a-=b*c;', 'a-=b*c;'];
    }
}
