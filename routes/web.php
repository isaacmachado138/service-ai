<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

Route::post('/ai/query', [AiController::class, 'query']);
