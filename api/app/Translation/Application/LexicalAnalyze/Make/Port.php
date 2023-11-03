<?php

declare(strict_types=1);

namespace app\Translation\Application\LexicalAnalyze\Make;

use app\Translation\Domain\Exception\UnsupportedTokenException;
use app\Translation\Domain\Repository\Exception\NotFoundException;
use app\Translation\Domain\ValueObject\Token;

interface Port
{
    /**
     * @throws NotFoundException
     * @return Token[]
     * @throws UnsupportedTokenException
     */
    public function handle(Command $command): array;
}
