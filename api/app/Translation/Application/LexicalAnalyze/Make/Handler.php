<?php

declare(strict_types=1);

namespace app\Translation\Application\LexicalAnalyze\Make;

use app\Translation\Domain\Aggregate\Translation;
use app\Translation\Domain\Exception\UnsupportedTokenException;
use app\Translation\Domain\Repository\Exception\NotFoundException;
use app\Translation\Domain\Repository\LanguageRepositoryInterface;
use app\Translation\Domain\ValueObject\Token;

readonly class Handler implements Port
{
    public function __construct(private LanguageRepositoryInterface $languageRepository)
    {
    }

    /**
     * @throws NotFoundException
     * @throws UnsupportedTokenException
     * @return Token[]
     */
    public function handle(Command $command): array
    {
        $language = $this->languageRepository->getLanguage($command->languageName);
        $translation = new Translation($language, $command->code);
        return $translation->getTokens();
    }
}
