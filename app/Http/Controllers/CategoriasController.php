<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriasRequest;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Illuminate\Support\Facades\DB;

class CategoriasController extends Controller
{
    
    public function index(Request $request)
    {
        try {
        $pagina = $request->input('pagina') ?? 1;
        $page = (! is_numeric($pagina)) || ($pagina < 1) ? 1 : $pagina;
        $perPage = $request->input('limite') ?? 10;


        $categorias = Categoria::orderBy('nome', 'asc')->paginate(perPage: $perPage, page: $page);
        $dados = [
            'total' => $categorias->total(),
            'por_pagina' => $categorias->perPage(),
            'categorias' => $categorias->items(),
            'ultima_pagina' => $categorias->lastPage(),
        ];
        return response()->json($dados, HttpStatus::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao listar categorias',
                'erro' => $e->getMessage(),
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(CategoriasRequest $request)
    {
        try {
            $categoria = Categoria::create([
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
            ]);
            $dados = [
                'mensagem' => 'Categoria criada com sucesso',
                'categoria' => $categoria,
            ];
            return response()->json($dados, HttpStatus::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao criar categoria',
                'erro' => $e->getMessage(),
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $categoria = Categoria::find($id);
            return response()->json($categoria, HttpStatus::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao exibir categoria',
                'erro' => $e->getMessage(),
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(CategoriasRequest $request, $id)
    {
        try {
            $categoria = Categoria::find($id);
            $categoria->update($request->all());
            return response()->json($categoria, HttpStatus::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao atualizar categoria',
                'erro' => $e->getMessage(),
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $categoria = Categoria::find($id);
            if (!empty($categoria)) {
                DB::table('produtos_categorias')->where('categoria_id', $id)->delete();
                $categoria->delete();
                return response()->json([
                    'mensagem' => 'Categoria deletada com sucesso',
                ], HttpStatus::HTTP_OK);
            } else {
                return response()->json([
                    'mensagem' => 'Categoria não encontrada',
                ], HttpStatus::HTTP_NOT_FOUND);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'mensagem' => 'Erro ao deletar categoria',
                'erro' => $e->getMessage(),
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
