<?php

declare(strict_types=1);

namespace app\Translation\Domain\Repository;

use app\Translation\Domain\Entity\Language\Language;
use app\Translation\Domain\Repository\Exception\NotFoundException;

interface LanguageRepositoryInterface
{
    /**
     * @throws NotFoundException
     */
    public function getLanguage(string $name): Language;
}
