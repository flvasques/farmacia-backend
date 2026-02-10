<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProdutosRequest;
use Illuminate\Support\Facades\DB;

class ProdutosController extends Controller
{
    public function index(Request $request)
    {
        try {

            $pagina = $request->input('pagina') ?? 1;
            $page = (! is_numeric($pagina)) || ($pagina < 1) ? 1 : $pagina;
            $perPage = $request->input('limite') ?? 10;
            $nome = $request->input('nome') ?? '';
            $categorias = $request->input('categorias') ?? '';
            $categorias = !empty($categorias) ? explode(',', $categorias) : [];
            $categorias = array_map('intval', $categorias);
            $orderBy = 'nome';
            $orderType = 'asc';
        
            switch ($request->input('order_by')) {
                case 'az':
                    $orderBy = 'nome';
                    $orderType = 'asc';
                    break;
                case 'za':
                    $orderBy = 'nome';
                    $orderType = 'desc';
                    break;
                case 'preco_asc':
                    $orderBy = 'preco';
                    $orderType = 'asc';
                    break;
                case 'preco_desc':
                    $orderBy = 'preco';
                    $orderType = 'desc';
                    break;
                default:
                    
            }

            $produtos = Produto::select('produtos.*')
                ->leftJoin('produtos_categorias', 'produtos.id', '=', 'produtos_categorias.produto_id')
                ->where(function ($query) use ($nome) {
                    if (!empty($nome)) {
                        $query->where('produtos.nome', 'like', "%$nome%");
                    }
                })
                ->where(function ($query) use ($categorias) {
                    if (!empty($categorias)) {
                        $query->whereIn('produtos_categorias.categoria_id', $categorias);
                    }
                })
                ->orderBy($orderBy, $orderType)
                ->orderBy('produtos.estoque', 'desc')
                ->paginate(perPage: $perPage, page: $page);

            $dados = [
                'total' => $produtos->total(),
                'por_pagina' => $produtos->perPage(),
                'produtos' => $produtos->items(),
                'ultima_pagina' => $produtos->lastPage(),
            ];
            return response()->json($dados, HttpStatus::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Erro ao listar produtos: ' . $e->getMessage());
            return response()->json([
                'mensagem' => 'Erro ao listar produtos',
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id)
    {
        try {
            $produto = Produto::with('categorias')->find($id);
            return response()->json($produto, HttpStatus::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Erro ao exibir produto: ' . $e->getMessage());
            return response()->json([
                'mensagem' => 'Erro ao exibir produto',
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ProdutosRequest $request)
    {
        try {
            $dados = $request->validated();
            DB::beginTransaction();
            $produto = Produto::create([
                'nome' => $dados['nome'],
                'descricao' => $dados['descricao'],
                'preco' => $dados['preco'],
                'estoque' => $dados['estoque'],
            ]);
            if(!empty($dados['categorias']) && is_array($dados['categorias'])) {
                foreach($dados['categorias'] as $categoriaId) {
                    DB::table('produtos_categorias')->insert([
                        'produto_id' => $produto->id,
                        'categoria_id' => $categoriaId,
                    ]);
                }
            }
            DB::commit();
            $dados = [
                'mensagem' => 'Produto criado com sucesso',
                'produto' => $produto,
            ];
            return response()->json($dados, HttpStatus::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Erro ao criar produto: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'mensagem' => 'Erro ao criar produto',
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update(ProdutosRequest $request, int $id)
    {
        try {
            DB::beginTransaction();
            $dados = $request->validated();
            Log::info($id);
            Log::info($dados);
            $produto = Produto::find($id);
            if (!empty($produto)) {
                $produto->update($dados);
                DB::table('produtos_categorias')->where('produto_id', $produto->id)->delete();
                if(!empty($dados['categorias']) && is_array($dados['categorias'])) {
                    foreach($dados['categorias'] as $categoriaId) {
                        DB::table('produtos_categorias')->insert([
                            'produto_id' => $produto->id,
                            'categoria_id' => $categoriaId,
                        ]);
                    }
                }
                DB::commit();
                $dados = [
                    'mensagem' => 'Produto atualizado com sucesso',
                    'produto' => $produto,
                ];
                return response()->json($dados, HttpStatus::HTTP_OK);
            } else {
                return response()->json([
                    'mensagem' => 'Produto não encontrado',
                ], HttpStatus::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar produto: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'mensagem' => 'Erro ao atualizar produto',
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id)
    {
        try {
            $produto = Produto::find($id);
            if (!empty($produto)) {
                $produto->delete();
                return response()->json([
                    'mensagem' => 'Produto deletado com sucesso',
                ], HttpStatus::HTTP_OK);
            } else {
                return response()->json([
                    'mensagem' => 'Produto não encontrado',
                ], HttpStatus::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao deletar produto: ' . $e->getMessage());
            return response()->json([
                'mensagem' => 'Erro ao deletar produto',
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
