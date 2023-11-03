<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Output\Persistence\Doctrine\Entity;

use app\Translation\Domain\ValueObject\Grammar\GrammarRule;
use app\Translation\Domain\ValueObject\Grammar\RegexValue;
use app\Translation\Domain\ValueObject\Grammar\StringValue;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\Enum\GrammarRuleTypeEnum;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\Enum\LanguageTokenTypeEnum;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use UnexpectedValueException;

#[Entity]
#[Table(name: 'grammar_rule')]
class GrammarRuleEntity
{
    public function __construct(
        #[Id]
        #[Column(name: 'id', type: 'integer')]
        #[GeneratedValue('IDENTITY')]
        private ?int $id = null,
        #[Column(name: 'token', type: 'string', enumType: LanguageTokenTypeEnum::class)]
        private LanguageTokenTypeEnum $tokenType,
        #[Column(name: 'type', type: 'string', enumType: GrammarRuleTypeEnum::class)]
        private GrammarRuleTypeEnum $type,
        #[Column(name: 'rule', type: 'string')]
        private string $rule,
    ) {
    }

    public function getTokenType(): string
    {
        return $this->tokenType->value;
    }

    public function toDomainEntity(): GrammarRule
    {
        $rule = match ($this->type) {
            GrammarRuleTypeEnum::REGEX => new RegexValue($this->rule),
            GrammarRuleTypeEnum::STRING => new StringValue($this->rule),
            default => throw new UnexpectedValueException('Unsupported rule type'),
        };

        return new GrammarRule($rule);
    }
}
