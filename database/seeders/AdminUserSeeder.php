<?php

namespace Database\Seeders;

use App\Models\ArtisanProfile;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──
        $this->criarUsuario(
            name: 'Administrador',
            email: 'admin@pie.com',
            password: 'admin123',
            role: 'admin',
            clienteNome: 'Administrador',
            telefone: '11911111111'
        );

        // ── Comprador (usuário comum) ──
        $this->criarUsuario(
            name: 'Usuário Teste',
            email: 'user@pie.com',
            password: 'user123',
            role: 'user',
            clienteNome: 'Usuário Teste',
            telefone: '11999999999'
        );

        // ── Artesão aprovado 1 ──
        $user1 = $this->criarUsuario(
            name: 'Artesão Teste',
            email: 'artesao@teste.com',
            password: 'artesao123',
            role: 'artisan',
            clienteNome: 'Artesão Teste',
            telefone: '11988887777'
        );
        ArtisanProfile::create([
            'user_id' => $user1->id,
            'cpf' => '11122233344',
            'phone' => '11988887777',
            'specialty' => 'Artesanato em Cerâmica',
            'bio' => 'Artesão especializado em cerâmica artesanal.',
            'is_public' => true,
            'approved_at' => now(),
        ]);

        // ── Artesão aprovado 2 ──
        $user2 = $this->criarUsuario(
            name: 'Artesão Teste 2',
            email: 'artesao2@teste.com',
            password: 'artesao123',
            role: 'artisan',
            clienteNome: 'Artesão Teste 2',
            telefone: '11966665555'
        );
        ArtisanProfile::create([
            'user_id' => $user2->id,
            'cpf' => '55566677788',
            'phone' => '11966665555',
            'specialty' => 'Bordado e Renda',
            'bio' => 'Bordadeira tradicional com mais de 20 anos de experiência.',
            'is_public' => false,
            'approved_at' => now(),
        ]);
    }

    private function criarUsuario(
        string $name,
        string $email,
        string $password,
        string $role,
        string $clienteNome,
        string $telefone = '00000000000',
        string $endereco = ''
    ): User {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => $role,
                'is_active' => true,
            ]
        );

        Cliente::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nome' => $clienteNome,
                'email' => $email,
                'telefone' => $telefone,
                'endereco' => $endereco,
            ]
        );

        return $user;
    }
}
