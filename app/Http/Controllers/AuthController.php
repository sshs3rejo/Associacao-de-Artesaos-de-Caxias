<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Processa o login do usuário
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Verifica se o usuário é admin (Restrição solicitada)
            if (! Auth::user()->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Acesso restrito apenas para administradores.',
                ]);
            }

            // Verifica se o usuário está ativo
            if (! Auth::user()->isActive()) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Sua conta está inativa. Entre em contato com o administrador.',
                ]);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Redireciona para o provedor social
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Callback do provedor social
     */
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
            // Atualiza o ID social se necessário
            if (!$user->{$provider . '_id'}) {
                $user->update([$provider . '_id' => $socialUser->getId()]);
            }
        } else {
            // Se o usuário não existe, poderíamos criar, mas a regra é "apenas admin"
            // Então, se não for admin já cadastrado, bloqueamos
            return redirect()->route('login.form')->withErrors(['email' => 'Esta conta social não está vinculada a um administrador.']);
        }

        if (!$user->isAdmin()) {
            return redirect()->route('login.form')->withErrors(['email' => 'Acesso restrito apenas para administradores.']);
        }

        Auth::login($user);
        return redirect()->route('admin.dashboard');
    }

    /**
     * Exibe o formulário de registro
     */
    public function register()
    {
        return view('register');
    }

    /**
     * Processa o registro de novo usuário
     */
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
            'role' => 'user', // Usuários registrados são sempre 'user' por padrão
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Conta criada com sucesso!');
    }

    /**
     * Faz logout do usuário
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout realizado com sucesso!');
    }
}
