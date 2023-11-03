<?php

declare(strict_types=1);

namespace app\Translation\Application\Admin\Language\Create;

readonly class Command
{
    /**
     * @param string $name
     * @param array<string,array{tokenType: string, type: string, rule: string}> $grammarRules
     */
    public function __construct(
        public string $name,
        public array $grammarRules,
    ) {
    }
}
