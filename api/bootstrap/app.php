<?php

use app\Framework\Application\Console\Kernel as ApplicationConsoleKernel;
use app\Framework\Application\Exceptions\Handler;
use app\Framework\Application\Http\Kernel as ApplicationHttpKernel;
use Illuminate\Contracts\Console\Kernel as FrameworkConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel as FrameworkHttpKernel;
use Illuminate\Foundation\Application;

$app = new Application($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));

$app->singleton(FrameworkHttpKernel::class, ApplicationHttpKernel::class);
$app->singleton(FrameworkConsoleKernel::class, ApplicationConsoleKernel::class);
$app->singleton(ExceptionHandler::class, Handler::class);

return $app;
