<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;
use App\Models\AiLog;

class AiController extends Controller
{
    public function query(Request $request, AiService $aiService)
    {
        $request->validate(['prompt' => 'required|string']);
        $start = microtime(true);

        $response = $aiService->sendPrompt($request->prompt);

        $executionTime = microtime(true) - $start;

        AiLog::create([
            'provider' => config('prism.provider'),
            'model' => config('prism.model'),
            'prompt' => $request->prompt,
            'response' => $response,
            'execution_time' => $executionTime,
        ]);

        return response()->json(['response' => $response]);
    }
}
