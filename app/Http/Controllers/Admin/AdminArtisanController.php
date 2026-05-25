<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
