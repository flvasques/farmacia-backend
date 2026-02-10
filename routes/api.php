<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\UserControler;
use App\Http\Controllers\ProdutosController;

Route::post('/login', [UserControler::class, 'login']);
Route::get('/categorias', [CategoriasController::class, 'index']);
Route::get('/categorias/{id}', [CategoriasController::class, 'show']);
Route::get('/produtos', [ProdutosController::class, 'index']);
Route::get('/produtos/{id}', [ProdutosController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::get('/usuario/perfil', [UserControler::class, 'perfil']);
    Route::post('/logout', [UserControler::class, 'logout']);
    Route::post('/categorias', [CategoriasController::class, 'store']);
    Route::put('/categorias/{id}', [CategoriasController::class, 'update']);
    Route::delete('/categorias/{id}', [CategoriasController::class, 'destroy']);
    Route::post('/produtos', [ProdutosController::class, 'store']);
    Route::put('/produtos/{id}', [ProdutosController::class, 'update']);
    Route::delete('/produtos/{id}', [ProdutosController::class, 'destroy']);
});
