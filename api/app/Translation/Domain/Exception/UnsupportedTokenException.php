<?php

declare(strict_types=1);

namespace app\Translation\Domain\Exception;

use Exception;

class UnsupportedTokenException extends Exception
{
    private int $linePosition;
    private int $columnPosition;
    private string $value;

    public function withLinePosition(int $linePosition): self
    {
        $this->linePosition = $linePosition;
        return $this;
    }

    public function withColumnPosition(int $columnPosition): self
    {
        $this->columnPosition = $columnPosition;
        return $this;
    }

    public function withValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getContext(): array
    {
        return [
            'line' => $this->linePosition,
            'column' => $this->columnPosition,
            'value' => $this->value,
        ];
    }
}
