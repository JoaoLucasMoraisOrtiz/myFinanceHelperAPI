<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Mostrar dashboard administrativo
     */
    public function dashboard()
    {
        $users = User::withCount('financas')->get();
        return view('admin.dashboard', compact('users'));
    }

    /**
     * Mostrar formulário de criação de usuário
     */
    public function create()
    {
        return view('admin.create-user');
    }

    /**
     * Criar novo usuário
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Deletar usuário
     */
    public function destroy(User $user)
    {
        // Não permitir deletar se for o último usuário
        if (User::count() <= 1) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Não é possível deletar o último usuário!');
        }

        $user->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Usuário deletado com sucesso!');
    }

    /**
     * Ver dados financeiros do usuário
     */
    public function viewFinances(User $user)
    {
        $financas = $user->financas;
        $dados = $financas ? $financas->data_json : [
            'receitas' => [],
            'despesas' => [],
            'planejamento' => ['receitas' => [], 'despesas' => []]
        ];

        return view('admin.view-finances', compact('user', 'dados'));
    }
}
