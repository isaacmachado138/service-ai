<?php

namespace App\Http\Controllers;

use App\Models\PromptTemplate;
use Illuminate\Http\Request;
use App\Services\Templates\TemplateManager;

/**
 * Controlador para gerenciamento de templates de prompt
 */
class PromptTemplateController extends Controller
{
    /**
     * Lista todos os templates disponíveis com opção de filtro por status
     *
     * @param Request $request Objeto de requisição contendo:
     *                         - active: (opcional) Filtro de status ativo (boolean)
     * 
     * @return \Illuminate\Http\JsonResponse Lista de templates encontrados
     */
    public function index(Request $request)
    {
        $templates = PromptTemplate::query();
        
        if ($request->has('active')) {
            $templates->where('is_active', $request->boolean('active'));
        }
        
        $result = $templates->get();
        
        return response()->json(['templates' => $result]);
    }
    
    /**
     * Obtém um template específico pelo seu código
     *
     * @param string $code Código identificador único do template
     * 
     * @return \Illuminate\Http\JsonResponse Detalhes do template
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Quando o template não é encontrado
     */
    public function show($code)
    {
        $template = PromptTemplate::where('code', $code)->firstOrFail();
        return response()->json(['template' => $template]);
    }
    
    /**
     * Cria um novo template de prompt
     *
     * @param Request $request Objeto de requisição contendo:
     *                         - code: (opcional) Código identificador único
     *                         - name: Nome do template
     *                         - system_prompt: Prompt de sistema
     *                         - user_prompt: Prompt do usuário com marcadores de variáveis
     *                         - description: (opcional) Descrição do template
     * 
     * @return \Illuminate\Http\JsonResponse Template criado e mensagem de confirmação
     * 
     * @throws \Illuminate\Validation\ValidationException Quando a validação falha
     */
    public function create(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string|unique:prompt_templates,code',
            'name' => 'required|string',
            'system_prompt' => 'required|string',
            'user_prompt' => 'required|string',
            'description' => 'nullable|string',
        ]);
        
        $template = PromptTemplate::create($request->all());
        
        return response()->json(['template' => $template, 'message' => 'Template criado com sucesso'], 201);
    }
    
    /**
     * Atualiza um template existente
     *
     * @param Request $request Objeto de requisição contendo os campos a serem atualizados
     * @param string $code Código identificador único do template
     * 
     * @return \Illuminate\Http\JsonResponse Template atualizado e mensagem de confirmação
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Quando o template não é encontrado
     * @throws \Illuminate\Validation\ValidationException Quando a validação falha
     */
    public function update(Request $request, $code)
    {
        $template = PromptTemplate::where('code', $code)->firstOrFail();
        
        $request->validate([
            'name' => 'string',
            'system_prompt' => 'string',
            'user_prompt' => 'string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $template->update($request->all());
        
        // Limpar o cache para este template
        TemplateManager::clearCache($template->code);
        
        return response()->json(['template' => $template, 'message' => 'Template atualizado com sucesso']);
    }
    
    /**
     * Remove um template existente
     *
     * @param string $code Código identificador único do template
     * 
     * @return \Illuminate\Http\JsonResponse Mensagem de confirmação da remoção
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Quando o template não é encontrado
     */
    public function destroy($code)
    {
        $template = PromptTemplate::where('code', $code)->firstOrFail();
        $templateCode = $template->code;
        
        $template->delete();
        
        // Limpar o cache para este template
        TemplateManager::clearCache($templateCode);
        
        return response()->json(['message' => 'Template removido com sucesso']);
    }
    
    /**
     * Testa um template com variáveis específicas sem executar a IA
     *
     * @param Request $request Objeto de requisição contendo:
     *                         - variables: Array de variáveis para substituir no template
     * @param string $code Código identificador único do template
     * 
     * @return \Illuminate\Http\JsonResponse Template, variáveis e prompt formatado
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Quando o template não é encontrado
     */
    public function test(Request $request, $code)
    {
        $template = PromptTemplate::where('code', $code)->firstOrFail();
        $variables = $request->input('variables', []);
        
        $formattedPrompt = $template->formatUserPrompt($variables);
        
        return response()->json([
            'template' => $template,
            'system_prompt' => $template->system_prompt,
            'formatted_user_prompt' => $formattedPrompt,
            'variables' => $variables
        ]);
    }
}
