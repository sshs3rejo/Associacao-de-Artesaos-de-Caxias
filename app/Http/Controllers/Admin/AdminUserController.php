<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\ActivityLog;
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
        if ($user->id === auth()->id()) {
            return back()->withErrors(['msg' => 'Você não pode desativar a si mesmo.']);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'ativado' : 'desativado';

        ActivityLog::log("usuario.{$status}", "Usuário {$user->name} {$status}.", $user);

        return back()->with('success', "Status do usuário {$user->name} alterado para {$status} com sucesso!");
    }

    public function changeRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['msg' => 'Você não pode alterar sua própria função.']);
        }

        $request->validate([
            'role' => 'required|in:admin,artisan,user'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        ActivityLog::log('usuario.role_alterada', "Função de {$user->name} alterada para {$request->role}.", $user);

        return back()->with('success', "Função (Role) do usuário {$user->name} atualizada com sucesso!");
    }

    public function create()
    {
        return view('admin.usuarios-create');
    }

    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        $validated['role'] = $request->input('role', 'user');
        $validated['is_active'] = true;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        ActivityLog::log('usuario.criado', "Usuário {$user->name} criado por administrador.", $user);

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        return view('admin.usuarios-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        ActivityLog::log('usuario.atualizado', "Usuário {$user->name} atualizado por administrador.", $user);

        return redirect()->route('admin.usuarios')
            ->with('success', "Usuário {$user->name} atualizado com sucesso!");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['msg' => 'Você não pode excluir a si mesmo.']);
        }

        ActivityLog::log('usuario.removido', "Usuário {$user->name} removido.", $user);

        if ($user->artisanProfile) {
            $user->artisanProfile->delete();
        }

        $user->delete();

        return back()->with('success', "Usuário {$user->name} excluído do sistema permanentemente!");
    }
}
