<?php

declare(strict_types=1);

namespace app\Translation\Application\Admin\Language\Create;

use app\Translation\Application\EntityManager\EntityManagerInterface;
use app\Translation\Domain\Entity\Language\Grammar;
use app\Translation\Domain\Entity\Language\Language;
use app\Translation\Domain\Enum\GrammarRuleType;
use app\Translation\Domain\ValueObject\Grammar\RegexValue;
use app\Translation\Domain\ValueObject\Grammar\StringValue;
use UnexpectedValueException;

readonly class Handler implements Port
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(Command $command): void
    {
        $grammarRules = [];
        foreach ($command->grammarRules as $grammarRule) {
            $grammarRules[$grammarRule['tokenType']][] = match ($grammarRule['type']) {
                GrammarRuleType::REGEX->value => new RegexValue($grammarRule['rule']),
                GrammarRuleType::STRING->value => new StringValue($grammarRule['rule']),
                default => throw new UnexpectedValueException('Not supported rule type'),
            };
        }
        $language = new Language($command->name, new Grammar($grammarRules));

        $this->entityManager->persist($language);
        $this->entityManager->flush();
    }
}
