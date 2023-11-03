<?php

declare(strict_types=1);

use app\Translation\Infrastructure\Input\Http\CreateLanguageController;
use app\Translation\Infrastructure\Input\Http\MakeLexicalAnalyzeController;
use Illuminate\Support\Facades\Route;

Route::post('/lexical', [MakeLexicalAnalyzeController::class, 'make'])->name('lexical.analyze');
Route::post('/admin/language', [CreateLanguageController::class, 'create'])->name('admin.language.create');
