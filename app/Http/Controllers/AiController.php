<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;
use App\Models\AiLog;

class AiController extends Controller
{
    public function query(Request $request)
    {
        // Não processa prompt nem chama serviço de IA
        return response()->json([
            'response' => 'Resposta temporariamente indisponível.'
        ], 503);
    }
}
