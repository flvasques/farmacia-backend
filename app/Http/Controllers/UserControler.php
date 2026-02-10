<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class UserControler extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();

            $token = Auth::guard('api')->attempt($data);

            if (!$token) {
                return response()->json(['message' => 'Credenciais inválidas.'], 401);
            }

            $user = Auth::guard('api')->user();

            return response()->json([
                'token_type' => 'Bearer',
                'token' => $token,
                'user' => $user,
            ], HttpStatus::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao fazer login',
                'error' => $e->getMessage(),
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function perfil(Request $request)
    {
        return response()->json(Auth::guard('api')->user());
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('api')->logout();

            return response()->json([
                'message' => 'Logout realizado.',
            ], HttpStatus::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao fazer logout',
                'error' => $e->getMessage(),
            ], HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
