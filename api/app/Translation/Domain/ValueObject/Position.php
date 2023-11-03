<?php

declare(strict_types=1);

namespace app\Translation\Domain\ValueObject;

use DomainException;

final readonly class Position
{
    public function __construct(public int $positionLine, public int $positionColumn)
    {
        if ($this->positionLine < 0 || $this->positionColumn < 0) {
            throw new DomainException('Line or column must be positive');
        }
    }

    public function __toString(): string
    {
        return $this->positionLine . ':' . $this->positionColumn;
    }
}
