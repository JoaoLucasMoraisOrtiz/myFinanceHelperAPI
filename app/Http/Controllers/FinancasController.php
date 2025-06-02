<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Financas;

class FinancasController extends Controller
{
    /**
     * Obter todos os dados financeiros do usuário
     */
    public function getAll(Request $request)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);

        return response()->json([
            'success' => true,
            'data' => $dados
        ]);
    }

    /**
     * Atualizar todos os dados financeiros do usuário
     */
    public function updateAll(Request $request)
    {
        $user = $request->authenticated_user;
        
        $validator = Validator::make($request->all(), [
            'receitas' => 'array',
            'despesas' => 'array',
            'planejamento' => 'array',
            'planejamento.receitas' => 'array',
            'planejamento.despesas' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $dados = $request->only(['receitas', 'despesas', 'planejamento']);
        
        // Garantir estrutura padrão
        if (!isset($dados['receitas'])) $dados['receitas'] = [];
        if (!isset($dados['despesas'])) $dados['despesas'] = [];
        if (!isset($dados['planejamento'])) $dados['planejamento'] = ['receitas' => [], 'despesas' => []];

        Financas::salvarDadosUsuario($user->id, $dados);

        return response()->json([
            'success' => true,
            'message' => 'Dados atualizados com sucesso'
        ]);
    }

    /**
     * Obter receitas
     */
    public function getReceitas(Request $request)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);

        return response()->json([
            'success' => true,
            'data' => $dados['receitas'] ?? []
        ]);
    }

    /**
     * Adicionar receita
     */
    public function addReceita(Request $request)
    {
        $user = $request->authenticated_user;
        
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'valor' => 'required|numeric|min:0',
            'data' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $dados = Financas::getDadosUsuario($user->id);
        
        $novaReceita = [
            'id' => time() * 1000 + rand(0, 999), // ID único similar ao do JSON
            'descricao' => $request->descricao,
            'valor' => (float) $request->valor,
            'data' => $request->data
        ];

        $dados['receitas'][] = $novaReceita;
        Financas::salvarDadosUsuario($user->id, $dados);

        return response()->json([
            'success' => true,
            'message' => 'Receita adicionada com sucesso',
            'data' => $novaReceita
        ]);
    }

    /**
     * Atualizar receita
     */
    public function updateReceita(Request $request, $id)
    {
        $user = $request->authenticated_user;
        
        $validator = Validator::make($request->all(), [
            'descricao' => 'string',
            'valor' => 'numeric|min:0',
            'data' => 'date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $dados = Financas::getDadosUsuario($user->id);
        
        foreach ($dados['receitas'] as $key => $receita) {
            if ($receita['id'] == $id) {
                if ($request->has('descricao')) $dados['receitas'][$key]['descricao'] = $request->descricao;
                if ($request->has('valor')) $dados['receitas'][$key]['valor'] = (float) $request->valor;
                if ($request->has('data')) $dados['receitas'][$key]['data'] = $request->data;
                
                Financas::salvarDadosUsuario($user->id, $dados);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Receita atualizada com sucesso',
                    'data' => $dados['receitas'][$key]
                ]);
            }
        }

        return response()->json([
            'error' => 'Receita não encontrada'
        ], 404);
    }

    /**
     * Deletar receita
     */
    public function deleteReceita(Request $request, $id)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);
        
        foreach ($dados['receitas'] as $key => $receita) {
            if ($receita['id'] == $id) {
                unset($dados['receitas'][$key]);
                $dados['receitas'] = array_values($dados['receitas']); // Reindexar array
                
                Financas::salvarDadosUsuario($user->id, $dados);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Receita deletada com sucesso'
                ]);
            }
        }

        return response()->json([
            'error' => 'Receita não encontrada'
        ], 404);
    }

    /**
     * Obter despesas
     */
    public function getDespesas(Request $request)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);

        return response()->json([
            'success' => true,
            'data' => $dados['despesas'] ?? []
        ]);
    }

    /**
     * Adicionar despesa
     */
    public function addDespesa(Request $request)
    {
        $user = $request->authenticated_user;
        
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'categoria' => 'required|string',
            'valor' => 'required|numeric|min:0',
            'data' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $dados = Financas::getDadosUsuario($user->id);
        
        $novaDespesa = [
            'id' => time() * 1000 + rand(0, 999),
            'descricao' => $request->descricao,
            'categoria' => $request->categoria,
            'valor' => (float) $request->valor,
            'data' => $request->data
        ];

        $dados['despesas'][] = $novaDespesa;
        Financas::salvarDadosUsuario($user->id, $dados);

        return response()->json([
            'success' => true,
            'message' => 'Despesa adicionada com sucesso',
            'data' => $novaDespesa
        ]);
    }

    /**
     * Atualizar despesa
     */
    public function updateDespesa(Request $request, $id)
    {
        $user = $request->authenticated_user;
        
        $validator = Validator::make($request->all(), [
            'descricao' => 'string',
            'categoria' => 'string',
            'valor' => 'numeric|min:0',
            'data' => 'date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $dados = Financas::getDadosUsuario($user->id);
        
        foreach ($dados['despesas'] as $key => $despesa) {
            if ($despesa['id'] == $id) {
                if ($request->has('descricao')) $dados['despesas'][$key]['descricao'] = $request->descricao;
                if ($request->has('categoria')) $dados['despesas'][$key]['categoria'] = $request->categoria;
                if ($request->has('valor')) $dados['despesas'][$key]['valor'] = (float) $request->valor;
                if ($request->has('data')) $dados['despesas'][$key]['data'] = $request->data;
                
                Financas::salvarDadosUsuario($user->id, $dados);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Despesa atualizada com sucesso',
                    'data' => $dados['despesas'][$key]
                ]);
            }
        }

        return response()->json([
            'error' => 'Despesa não encontrada'
        ], 404);
    }

    /**
     * Deletar despesa
     */
    public function deleteDespesa(Request $request, $id)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);
        
        foreach ($dados['despesas'] as $key => $despesa) {
            if ($despesa['id'] == $id) {
                unset($dados['despesas'][$key]);
                $dados['despesas'] = array_values($dados['despesas']);
                
                Financas::salvarDadosUsuario($user->id, $dados);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Despesa deletada com sucesso'
                ]);
            }
        }

        return response()->json([
            'error' => 'Despesa não encontrada'
        ], 404);
    }

    /**
     * Obter planejamento
     */
    public function getPlanejamento(Request $request)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);

        return response()->json([
            'success' => true,
            'data' => $dados['planejamento'] ?? ['receitas' => [], 'despesas' => []]
        ]);
    }

    /**
     * Adicionar receita ao planejamento
     */
    public function addReceitaPlanejamento(Request $request)
    {
        $user = $request->authenticated_user;
        
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'valor' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $dados = Financas::getDadosUsuario($user->id);
        
        $novaReceita = [
            'id' => time() * 1000 + rand(0, 999),
            'descricao' => $request->descricao,
            'valor' => (float) $request->valor
        ];

        if (!isset($dados['planejamento'])) {
            $dados['planejamento'] = ['receitas' => [], 'despesas' => []];
        }

        $dados['planejamento']['receitas'][] = $novaReceita;
        Financas::salvarDadosUsuario($user->id, $dados);

        return response()->json([
            'success' => true,
            'message' => 'Receita adicionada ao planejamento com sucesso',
            'data' => $novaReceita
        ]);
    }

    /**
     * Adicionar despesa ao planejamento
     */
    public function addDespesaPlanejamento(Request $request)
    {
        $user = $request->authenticated_user;
        
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'categoria' => 'required|string',
            'valorTotal' => 'required|numeric|min:0',
            'valor' => 'required|numeric|min:0',
            'parcelada' => 'boolean',
            'numParcelas' => 'nullable|integer|min:1',
            'parcelaAtual' => 'nullable|integer|min:1',
            'taxaJuros' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $dados = Financas::getDadosUsuario($user->id);
        
        $novaDespesa = [
            'id' => time() * 1000 + rand(0, 999),
            'descricao' => $request->descricao,
            'categoria' => $request->categoria,
            'valorTotal' => (float) $request->valorTotal,
            'valor' => (float) $request->valor,
            'parcelada' => $request->boolean('parcelada', false)
        ];

        if ($novaDespesa['parcelada']) {
            $novaDespesa['numParcelas'] = $request->numParcelas ?? 1;
            $novaDespesa['parcelaAtual'] = $request->parcelaAtual ?? 1;
            $novaDespesa['taxaJuros'] = (float) ($request->taxaJuros ?? 0);
        }

        if (!isset($dados['planejamento'])) {
            $dados['planejamento'] = ['receitas' => [], 'despesas' => []];
        }

        $dados['planejamento']['despesas'][] = $novaDespesa;
        Financas::salvarDadosUsuario($user->id, $dados);

        return response()->json([
            'success' => true,
            'message' => 'Despesa adicionada ao planejamento com sucesso',
            'data' => $novaDespesa
        ]);
    }

    // Métodos para atualizar e deletar itens do planejamento seguem o mesmo padrão
    // dos métodos de receitas e despesas normais, mas operando sobre
    // $dados['planejamento']['receitas'] e $dados['planejamento']['despesas']

    public function updateReceitaPlanejamento(Request $request, $id)
    {
        $user = $request->authenticated_user;
        
        $validator = Validator::make($request->all(), [
            'descricao' => 'string',
            'valor' => 'numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $dados = Financas::getDadosUsuario($user->id);
        
        if (!isset($dados['planejamento']['receitas'])) {
            return response()->json(['error' => 'Receita não encontrada'], 404);
        }

        foreach ($dados['planejamento']['receitas'] as $key => $receita) {
            if ($receita['id'] == $id) {
                if ($request->has('descricao')) $dados['planejamento']['receitas'][$key]['descricao'] = $request->descricao;
                if ($request->has('valor')) $dados['planejamento']['receitas'][$key]['valor'] = (float) $request->valor;
                
                Financas::salvarDadosUsuario($user->id, $dados);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Receita do planejamento atualizada com sucesso',
                    'data' => $dados['planejamento']['receitas'][$key]
                ]);
            }
        }

        return response()->json(['error' => 'Receita não encontrada'], 404);
    }

    public function updateDespesaPlanejamento(Request $request, $id)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);
        
        if (!isset($dados['planejamento']['despesas'])) {
            return response()->json(['error' => 'Despesa não encontrada'], 404);
        }

        foreach ($dados['planejamento']['despesas'] as $key => $despesa) {
            if ($despesa['id'] == $id) {
                if ($request->has('descricao')) $dados['planejamento']['despesas'][$key]['descricao'] = $request->descricao;
                if ($request->has('categoria')) $dados['planejamento']['despesas'][$key]['categoria'] = $request->categoria;
                if ($request->has('valorTotal')) $dados['planejamento']['despesas'][$key]['valorTotal'] = (float) $request->valorTotal;
                if ($request->has('valor')) $dados['planejamento']['despesas'][$key]['valor'] = (float) $request->valor;
                if ($request->has('parcelada')) $dados['planejamento']['despesas'][$key]['parcelada'] = $request->boolean('parcelada');
                
                Financas::salvarDadosUsuario($user->id, $dados);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Despesa do planejamento atualizada com sucesso',
                    'data' => $dados['planejamento']['despesas'][$key]
                ]);
            }
        }

        return response()->json(['error' => 'Despesa não encontrada'], 404);
    }

    public function deleteReceitaPlanejamento(Request $request, $id)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);
        
        if (!isset($dados['planejamento']['receitas'])) {
            return response()->json(['error' => 'Receita não encontrada'], 404);
        }

        foreach ($dados['planejamento']['receitas'] as $key => $receita) {
            if ($receita['id'] == $id) {
                unset($dados['planejamento']['receitas'][$key]);
                $dados['planejamento']['receitas'] = array_values($dados['planejamento']['receitas']);
                
                Financas::salvarDadosUsuario($user->id, $dados);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Receita do planejamento deletada com sucesso'
                ]);
            }
        }

        return response()->json(['error' => 'Receita não encontrada'], 404);
    }

    public function deleteDespesaPlanejamento(Request $request, $id)
    {
        $user = $request->authenticated_user;
        $dados = Financas::getDadosUsuario($user->id);
        
        if (!isset($dados['planejamento']['despesas'])) {
            return response()->json(['error' => 'Despesa não encontrada'], 404);
        }

        foreach ($dados['planejamento']['despesas'] as $key => $despesa) {
            if ($despesa['id'] == $id) {
                unset($dados['planejamento']['despesas'][$key]);
                $dados['planejamento']['despesas'] = array_values($dados['planejamento']['despesas']);
                
                Financas::salvarDadosUsuario($user->id, $dados);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Despesa do planejamento deletada com sucesso'
                ]);
            }
        }

        return response()->json(['error' => 'Despesa não encontrada'], 404);
    }
}
