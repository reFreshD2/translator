<?php

declare(strict_types=1);

namespace app\Translation\Application\LexicalAnalyze\Make;

readonly class Command
{
    public function __construct(
        public string $languageName,
        public string $code,
    ) {
    }
}
