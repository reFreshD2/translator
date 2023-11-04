<?php

declare(strict_types=1);

namespace Tests\Unit\Translation\Domain\ValueObject\Grammar;

use app\Translation\Domain\ValueObject\Grammar\StringValue;
use Codeception\Test\Unit;

class StringValueTest extends Unit
{
    private const STRING = 'for';

    private StringValue $stringValue;

    protected function _setUp(): void
    {
        $this->stringValue = new StringValue(self::STRING);
    }

    public function testEquals(): void
    {
        $this->assertTrue($this->stringValue->equals(self::STRING));
        $this->assertFalse($this->stringValue->equals('form'));
    }

    public function testFormat(): void
    {
        $this->assertEquals(' for  i:= 1 to 10 do', $this->stringValue->format('for i:= 1 to 10 do'));
        $this->assertEquals('while(a<10) do', $this->stringValue->format('while(a<10) do'));
    }
}
