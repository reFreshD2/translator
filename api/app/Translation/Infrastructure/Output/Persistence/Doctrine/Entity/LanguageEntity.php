<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Output\Persistence\Doctrine\Entity;

use app\Translation\Domain\Entity\Language\Grammar;
use app\Translation\Domain\Entity\Language\Language;
use app\Translation\Domain\ValueObject\Grammar\RegexValue;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\Enum\GrammarRuleTypeEnum;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\Enum\LanguageTokenTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use ReflectionClass;
use ReflectionException;

#[Entity]
#[Table(name: 'language')]
class LanguageEntity
{
    /**
     * @var Collection<int, GrammarRuleEntity>
     */
    #[JoinTable(name: 'language_grammar')]
    #[JoinColumn(name: 'language_name', referencedColumnName: 'name')]
    #[InverseJoinColumn(name: 'grammar_rule_id', referencedColumnName: 'id')]
    #[ManyToMany(targetEntity: GrammarRuleEntity::class, cascade: ['persist'])]
    private Collection $grammarRules;

    public function __construct(
        #[Id]
        #[Column(name: 'name', type: 'string')]
        private string $name,
    ) {
        $this->grammarRules = new ArrayCollection();
    }

    /**
     * @throws ReflectionException
     */
    public static function fromDomainEntity(Language $entity): self
    {
        /**
         * Костыль, чтобы доменная модель была инкапсулированна
         */
        $reflectionLanguage = new ReflectionClass($entity);
        $grammar = $reflectionLanguage->getProperty('grammar')->getValue($entity);
        $reflectionGrammar = new ReflectionClass($reflectionLanguage->getProperty('grammar')->getValue($entity));
        $grammarRuleSet = $reflectionGrammar->getProperty('ruleSet')->getValue($grammar);

        $persistenceEntity = new self($reflectionLanguage->getProperty('name')->getValue($entity));
        foreach ($grammarRuleSet as $tokenType => $rules) {
            foreach ($rules as $rule) {
                $reflectionRule = new ReflectionClass($rule);
                if ($rule instanceof RegexValue) {
                    $ruleType = GrammarRuleTypeEnum::REGEX;
                } else {
                    $ruleType = GrammarRuleTypeEnum::STRING;
                }

                $grammarRule = new GrammarRuleEntity(
                    null,
                    LanguageTokenTypeEnum::from($tokenType),
                    $ruleType,
                    $reflectionRule->getProperty('value')->getValue($rule),
                );
                $persistenceEntity->addRule($grammarRule);
            }
        }

        return $persistenceEntity;
    }

    public function toDomainEntity(): Language
    {
        $ruleSet = [];
        foreach ($this->grammarRules as $grammarRule) {
            $ruleSet[$grammarRule->getTokenType()][] = $grammarRule->toDomainEntity();
        }
        return new Language(
            $this->name,
            new Grammar($ruleSet),
        );
    }

    public function addRule(GrammarRuleEntity $grammarRule): void
    {
        $this->grammarRules->add($grammarRule);
    }
}
