<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Realizar login e retornar token de acesso
     */
    public function login(Request $request)
    {
        // Validar dados de entrada
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Dados inválidos',
                'messages' => $validator->errors()
            ], 400);
        }

        $email = $request->email;
        $password = $request->password;

        // Verificar se o usuário existe
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'error' => 'Credenciais inválidas',
                'message' => 'Email ou senha incorretos'
            ], 401);
        }

        // Gerar token (base64 de email:password)
        $token = base64_encode($email . ':' . $password);

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Validar token de acesso
     */
    public function validateToken(Request $request)
    {
        // Se chegou até aqui, o token é válido (passou pelo middleware)
        $user = $request->authenticated_user;

        return response()->json([
            'message' => 'Token válido',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
}
