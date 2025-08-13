<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

Route::post('/query', [AiController::class, 'query']);