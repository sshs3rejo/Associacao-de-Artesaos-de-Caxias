<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Idempotente: trunca e reinsere as categorias do zero.
     */
    public function run(): void
    {
        DB::table('categorias_produtos')->truncate();

        // Categorias principais
        DB::table('categorias_produtos')->insert([
            ['id_categoria' => 1, 'nome_categoria' => 'Artesanato em Madeira',          'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id_categoria' => 2, 'nome_categoria' => 'Artesanato em Tecidos',           'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id_categoria' => 4, 'nome_categoria' => 'Bijuterias e Biojoias',           'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id_categoria' => 5, 'nome_categoria' => 'Decoração',                       'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id_categoria' => 7, 'nome_categoria' => 'Artesanato em Fibras e Babaçu', 'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Reseta a sequence do PostgreSQL para não colidir com os IDs manuais
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("SELECT setval('categorias_produtos_id_categoria_seq', (SELECT MAX(id_categoria) FROM categorias_produtos))");
        }

        // Subcategorias de Artesanato em Tecidos (ID 2)
        DB::table('categorias_produtos')->insert([
            ['nome_categoria' => 'Pano de Prato',        'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nome_categoria' => 'Bonecas de Tecido',    'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nome_categoria' => 'Camisas Estampadas',   'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nome_categoria' => 'Supla e Mesa Posta',   'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Subcategorias de Artesanato em Fibras e Babaçu (ID 7)
        DB::table('categorias_produtos')->insert([
            ['nome_categoria' => 'Decoração e Lembrancinhas', 'parent_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['nome_categoria' => 'Esculturas de Coco',        'parent_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['nome_categoria' => 'Porta Canetas',             'parent_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['nome_categoria' => 'Porta Moedas',              'parent_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['nome_categoria' => 'Cestas e Trançados',        'parent_id' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Subcategorias de Decoração (ID 5)
        DB::table('categorias_produtos')->insert([
            ['nome_categoria' => 'Jarros e Vasos', 'parent_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
