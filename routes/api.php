<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;
use App\Http\Controllers\PromptTemplateController;

// Rota de AI
Route::post('/query', [AiController::class, 'query']);
Route::get('/test-actions', [AiController::class, 'testActions']);

// Rotas para gerenciamento de templates
Route::prefix('templates')->group(function () {
    Route::get('/', [PromptTemplateController::class, 'index']);
    Route::get('/{code}', [PromptTemplateController::class, 'show']);
    Route::post('/', [PromptTemplateController::class, 'create']);
    Route::put('/{code}', [PromptTemplateController::class, 'update']);
    Route::delete('/{code}', [PromptTemplateController::class, 'destroy']);
    Route::post('/{code}/test', [PromptTemplateController::class, 'test']);
});