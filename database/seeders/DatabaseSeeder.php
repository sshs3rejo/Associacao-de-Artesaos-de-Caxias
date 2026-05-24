<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ordem correta de execução dos seeders
        $this->call([
            AdminUserSeeder::class,      // 1. Criar usuários admin e normal
            CategoriaSeeder::class,      // 2. Criar categorias de produtos
            ProdutoSeeder::class,        // 3. Criar produtos (depende de categorias)
            InstrutorSeeder::class,      // 4. Criar instrutores
            EventoSeeder::class,         // 5. Criar eventos (depende de instrutores)
        ]);

        $this->command->info('✅ Todos os dados foram inseridos com sucesso!');
        $this->command->info('');
        $this->command->info('📊 Resumo:');
        $this->command->info('   - 4 usuários (1 admin, 1 comprador, 2 artesãos aprovados)');
        $this->command->info('   - 2 perfis de artesão (aprovados)');
        $this->command->info('   - 5 categorias principais + 10 subcategorias');
        $this->command->info('   - 20 produtos artesanais reais (baseados no PDF)');
        $this->command->info('   - 3 instrutores');
        $this->command->info('   - 3 eventos');
    }
}
