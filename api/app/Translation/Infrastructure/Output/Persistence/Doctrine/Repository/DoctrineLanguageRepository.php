<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Output\Persistence\Doctrine\Repository;

use app\Translation\Domain\Entity\Language\Language;
use app\Translation\Domain\Repository\Exception\NotFoundException;
use app\Translation\Domain\Repository\LanguageRepositoryInterface;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\Entity\LanguageEntity;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineLanguageRepository implements LanguageRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getLanguage(string $name): Language
    {
        $language = $this->entityManager->find(LanguageEntity::class, $name);
        if ($language === null) {
            throw new NotFoundException('Language doesn\'t exist');
        }

        return $language->toDomainEntity();
    }
}
