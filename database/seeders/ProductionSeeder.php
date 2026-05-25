<?php

namespace Database\Seeders;

use App\Models\CategoriasProdutos;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('A criar dados essenciais para produção...');

        // Admin padrão
        $admin = User::firstOrCreate(
            ['email' => 'admin@pie.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        Cliente::firstOrCreate(
            ['user_id' => $admin->id],
            ['nome' => 'Administrador', 'email' => 'admin@pie.com', 'telefone' => '11911111111']
        );
        $this->command->info('   ✓ Admin padrão: admin@pie.com / admin123');

        // Categorias principais
        $cats = [
            ['nome_categoria' => 'Artesanato em Madeira', 'parent_id' => null],
            ['nome_categoria' => 'Artesanato em Tecidos', 'parent_id' => null],
            ['nome_categoria' => 'Bijuterias e Biojoias', 'parent_id' => null],
            ['nome_categoria' => 'Decoração', 'parent_id' => null],
            ['nome_categoria' => 'Artesanato em Fibras e Babaçu', 'parent_id' => null],
        ];
        foreach ($cats as $cat) {
            CategoriasProdutos::firstOrCreate(
                ['nome_categoria' => $cat['nome_categoria']],
                $cat
            );
        }
        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');
        $this->command->info('   ✓ 5 categorias principais criadas');

        $this->command->info('');
        $this->command->info('✅ Produção pronta! Altere o email/senha do admin após o primeiro acesso.');
    }
}
