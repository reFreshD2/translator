<?php

declare(strict_types=1);

namespace app\Translation\Application\EntityManager;

interface EntityManagerInterface
{
    public function persist(object $entity): void;
    public function flush(): void;
}
