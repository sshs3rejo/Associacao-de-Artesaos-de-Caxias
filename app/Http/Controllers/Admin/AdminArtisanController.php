<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class AdminArtisanController extends Controller
{
    public function index()
    {
        $artesos = User::where('role', 'artisan')
            ->with('artisanProfile')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.artesos', compact('artesos'));
    }

    public function create()
    {
        return view('admin.artesos.create');
    }

    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'artisan',
            'is_active' => true,
        ]);

        $user->artisanProfile()->create([
            'specialty' => $request->input('specialty', ''),
            'bio' => $request->input('bio', ''),
            'is_public' => $request->boolean('is_public', true),
            'approved_at' => now(),
        ]);

        ActivityLog::log('artesao.criado', "Artesão {$user->name} criado por administrador.", $user);

        return redirect()->route('admin.artesao')
            ->with('success', "Artesão {$user->name} cadastrado com sucesso!");
    }

    public function show(User $user)
    {
        if ($user->role !== 'artisan') {
            abort(404);
        }

        $user->load('artisanProfile');

        return view('admin.artesos.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role !== 'artisan') {
            abort(404);
        }

        $user->load('artisanProfile');

        return view('admin.artesos.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'artisan') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'specialty' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->artisanProfile) {
            $user->artisanProfile->update([
                'specialty' => $validated['specialty'] ?? '',
                'bio' => $validated['bio'] ?? '',
                'is_public' => $request->boolean('is_public', true),
            ]);
        }

        ActivityLog::log('artesao.atualizado', "Artesão {$user->name} atualizado por administrador.", $user);

        return redirect()->route('admin.artesao')
            ->with('success', "Artesão {$user->name} atualizado com sucesso!");
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'artisan') {
            return back()->withErrors(['msg' => 'Usuário não é artesão.']);
        }

        ActivityLog::log('artesao.removido', "Artesão {$user->name} removido.", $user);

        if ($user->artisanProfile) {
            $user->artisanProfile->delete();
        }

        $user->delete();

        return back()->with('success', "Artesão {$user->name} removido do sistema.");
    }

    public function aprovar(User $user)
    {
        if ($user->role !== 'artisan') {
            return back()->withErrors(['msg' => 'Usuário não é artesão.']);
        }

        $profile = $user->artisanProfile;
        if ($profile) {
            $profile->update(['approved_at' => now()]);
        }

        $user->update(['is_active' => true]);

        ActivityLog::log('artesao.aprovado', "Artesão {$user->name} aprovado.", $user);

        return back()->with('success', "Artesão {$user->name} aprovado com sucesso!");
    }

    public function rejeitar(User $user)
    {
        if ($user->role !== 'artisan') {
            return back()->withErrors(['msg' => 'Usuário não é artesão.']);
        }

        $profile = $user->artisanProfile;
        if ($profile) {
            $profile->update(['approved_at' => null]);
        }

        ActivityLog::log('artesao.rejeitado', "Perfil de artesão {$user->name} rejeitado.", $user);

        return back()->with('success', "Perfil de artesão {$user->name} foi rejeitado.");
    }

    public function ativar(User $user)
    {
        if ($user->role !== 'artisan') {
            return back()->withErrors(['msg' => 'Usuário não é artesão.']);
        }

        $user->update(['is_active' => true]);

        ActivityLog::log('artesao.ativado', "Artesão {$user->name} reativado.", $user);

        return back()->with('success', "Artesão {$user->name} foi reativado com sucesso!");
    }
}
