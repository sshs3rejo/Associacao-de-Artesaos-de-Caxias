<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\ActivityLog;
use App\Models\ArtisanProfile;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (! $user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Sua conta está inativa. Entre em contato com o administrador.',
                ])->onlyInput('email');
            }

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->isArtisan()) {
                $profile = $user->artisanProfile;
                if ($profile && $profile->isApproved()) {
                    return redirect()->intended(route('artesan.dashboard'));
                }
                return redirect()->intended(route('user.perfil'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }



    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, &$user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
                'is_active' => true,
            ]);

            Cliente::updateOrCreate(
                ['email' => $validated['email']],
                [
                    'user_id' => $user->id,
                    'nome' => $validated['name'],
                    'telefone' => '',
                    'endereco' => '',
                ]
            );
        });

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Cadastro realizado com sucesso!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logout realizado com sucesso!');
    }
}
