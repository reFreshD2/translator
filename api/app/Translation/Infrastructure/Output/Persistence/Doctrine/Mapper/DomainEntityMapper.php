<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Output\Persistence\Doctrine\Mapper;

use app\Translation\Domain\Entity\Language\Language;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\Entity\LanguageEntity;
use ReflectionException;
use UnexpectedValueException;

class DomainEntityMapper
{
    /**
     * @throws ReflectionException
     */
    public function map(object $entity): object
    {
        return match ($entity::class) {
            Language::class => LanguageEntity::fromDomainEntity($entity),
            default => throw new UnexpectedValueException('Unsupported Entity for mapper'),
        };
    }
}
