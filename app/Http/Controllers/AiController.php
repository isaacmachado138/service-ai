<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;
use App\Models\AiLog;

class AiController extends Controller
{
    public function query(Request $request)
    {
        //$request->validate(['prompt' => 'required|string']);
        //echo "Prompt validado: " . $request->input('prompt') . "\n";

        //echo "Chamando AiService...\n";
        $response = \App\Services\AiService::make()
            ->prompt($request->input('prompt'))
            ->execute();

        return response()->json([
            'text' => $response
        ]);
    }
}
