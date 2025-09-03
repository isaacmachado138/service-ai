<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;
use App\Services\Templates\TemplateManager;
use App\Services\Models\Tools\TestCommentsMockTool;
use App\Services\Models\Tools\TestUsersMockTool;

/**
 * Controlador responsável pelo processamento de consultas de IA
 */
class AiController extends Controller
{
    /**
     * Processa uma consulta de IA utilizando template ou prompt direto
     *
     * @param Request $request Objeto de requisição contendo:
     *                         - template: Código do template (opcional, default: 'default')
     *                         - variables: Array de variáveis para substituir no template
     *                         - prompt: Texto do prompt direto (opcional)
     * 
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo:
     *                                      - text: Texto da resposta gerada pela IA
     * 
     * @throws \InvalidArgumentException Se o template não for encontrado ou as variáveis necessárias não forem fornecidas
     */
    public function query(Request $request)
    {
        // Obter template code e variáveis do request
        $templateCode = $request->input('template', 'default');
        $variables = $request->input('variables', []);
        
        // Se prompt for enviado diretamente, adicione-o às variáveis
        if ($request->has('prompt')) {
            $variables['prompt'] = $request->input('prompt');
        }
        
        // Obter o template formatado (system_prompt e user_prompt)
        $template = TemplateManager::getFormattedTemplate($templateCode, $variables);
        
        // Executar o serviço com os prompts do template
        $response = \App\Services\AiService::make()
            ->with($template['system_prompt'])
            ->prompt($template['user_prompt'])
            ->execute();

        return response()->json([
            'text' => $response
        ]);
    }

    /**
     * Testa as actions com template fixo summary_comments_test
     *
     * @param Request $request Objeto de requisição contendo:
     *                         - postId: ID do post para buscar comentários (via query param)
     * 
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo:
     *                                      - text: Texto da resposta gerada pela IA
     */
    public function testActions(Request $request)
    {
        // Obter o ID do post da query string (default: 1)
        $postId = $request->query('postId', 1);
        
        // Usar template fixo 'summary_comments_test'
        $templateCode = 'summary_comments_test';
        $variables = ['postId' => $postId];
        
        // Obter o template formatado (system_prompt e user_prompt)
        $template = TemplateManager::getFormattedTemplate($templateCode, $variables);
        
        // Executar o serviço com os prompts do template e as ferramentas
        $response = \App\Services\AiService::make()
            ->with($template['system_prompt'])
            ->prompt($template['user_prompt'])
            ->tools([
                (new TestCommentsMockTool())->build(),
                (new TestUsersMockTool())->build()
            ])
            ->execute();

        return response()->json([
            'text' => $response,
            'postId' => $postId,
            'template' => $templateCode
        ]);
    }
}
