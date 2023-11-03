<?php

namespace app\Framework\Providers;

use app\Translation\Application\Admin\Language\Create\Handler as CreateLanguageHandler;
use app\Translation\Application\Admin\Language\Create\Port as CreateLanguagePort;
use app\Translation\Application\EntityManager\EntityManagerInterface;
use app\Translation\Application\LexicalAnalyze\Make\Handler as MakeLexicalAnalyzeHandler;
use app\Translation\Application\LexicalAnalyze\Make\Port as MakeLexicalAnalyzePort;
use app\Translation\Domain\Repository\LanguageRepositoryInterface;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\EntityManager\DoctrineEntityManager;
use app\Translation\Infrastructure\Output\Persistence\Doctrine\Repository\DoctrineLanguageRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LanguageRepositoryInterface::class, DoctrineLanguageRepository::class);
        $this->app->bind(MakeLexicalAnalyzePort::class, MakeLexicalAnalyzeHandler::class);
        $this->app->bind(EntityManagerInterface::class, DoctrineEntityManager::class);
        $this->app->bind(CreateLanguagePort::class, CreateLanguageHandler::class);
    }

    public function boot(): void
    {
    }
}
