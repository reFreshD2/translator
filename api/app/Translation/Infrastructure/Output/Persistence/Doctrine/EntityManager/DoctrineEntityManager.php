<?php

declare(strict_types=1);

namespace app\Translation\Infrastructure\Output\Persistence\Doctrine\EntityManager;

use app\Translation\Application\EntityManager\EntityManagerInterface;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\Mapper\DomainEntityMapper;
use Doctrine\ORM\EntityManagerInterface as DoctrineEntityManagerInterface;

readonly class DoctrineEntityManager implements EntityManagerInterface
{
    public function __construct(
        private DoctrineEntityManagerInterface $entityManager,
        private DomainEntityMapper $mapper,
    ) {
    }

    public function persist(object $entity): void
    {
        $mappedEntity = $this->mapper->map($entity);
        $this->entityManager->persist($mappedEntity);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
