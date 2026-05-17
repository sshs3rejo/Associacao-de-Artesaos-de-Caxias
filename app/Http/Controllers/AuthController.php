<?php

namespace App\Http\Controllers;

use App\Models\ArtisanProfile;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (! $user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Sua conta está inativa. Entre em contato com o administrador.',
                ]);
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

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login.form')->withErrors(['email' => 'Erro na autenticação social.']);
        }

        $user = User::where($provider . '_id', $socialUser->getId())
                    ->orWhere('email', $socialUser->getEmail())
                    ->first();

        if ($user) {
            if (! $user->{$provider . '_id'}) {
                $user->update([$provider . '_id' => $socialUser->getId()]);
            }
        } else {
            return redirect()->route('login.form')->withErrors(['email' => 'Conta não encontrada.']);
        }

        if (! $user->isActive()) {
            return redirect()->route('login.form')->withErrors(['email' => 'Sua conta está inativa.']);
        }

        Auth::login($user);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isArtisan()) {
            return redirect()->route('artesan.dashboard');
        }

        return redirect()->route('home');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

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
