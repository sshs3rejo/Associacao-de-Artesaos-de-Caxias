<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('artisanProfile')
            ->where('id', '!=', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.usuarios', compact('usuarios'));
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "Status do usuário {$user->name} alterado para {$status} com sucesso!");
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,artisan,user'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', "Função (Role) do usuário {$user->name} atualizada com sucesso!");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['msg' => 'Você não pode excluir a si mesmo.']);
        }

        if ($user->artisanProfile) {
            $user->artisanProfile->delete();
        }

        $user->delete();

        return back()->with('success', "Usuário {$user->name} excluído do sistema permanentemente!");
    }
}
