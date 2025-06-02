<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar se o token está presente no header Authorization
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'error' => 'Token de acesso não fornecido',
                'message' => 'Inclua o token no header Authorization como Bearer {token}'
            ], 401);
        }

        // Extrair o token
        $token = substr($authHeader, 7); // Remove "Bearer "

        // Decodificar o token (base64 encode de email:password)
        $decoded = base64_decode($token);
        
        if (!$decoded || !str_contains($decoded, ':')) {
            return response()->json([
                'error' => 'Token inválido',
                'message' => 'Formato do token é inválido'
            ], 401);
        }

        [$email, $password] = explode(':', $decoded, 2);

        // Verificar credenciais
        $user = User::where('email', $email)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'error' => 'Credenciais inválidas',
                'message' => 'Email ou senha incorretos'
            ], 401);
        }

        // Adicionar usuário ao request para usar nos controllers
        $request->merge(['authenticated_user' => $user]);

        return $next($request);
    }
}
