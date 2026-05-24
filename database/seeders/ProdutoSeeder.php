<?php

namespace Database\Seeders;

use App\Models\Produto;
use App\Models\CategoriasProdutos;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpar produtos e estoques existentes (ordem respeitando FKs)
        DB::statement('DELETE FROM _estoques');
        DB::statement('DELETE FROM itens_venda');
        DB::statement('DELETE FROM produto');

        // Obter artesão padrão
        $artesanId = User::where('role', 'artisan')->first()?->id ?? null;

        // Função auxiliar para obter id_categoria
        $getCategoriaId = function ($nome) {
            return CategoriasProdutos::where('nome_categoria', $nome)->value('id_categoria');
        };

        // Lista de produtos reais baseada no PDF
        $produtos = [
            // --- 🧵 Artesanato em Tecidos ---
            [
                'nome' => 'Pano de Prato em Pintura e Crochê',
                'descricao' => 'Pano de prato artesanal de alta qualidade com barra decorativa em crochê e pintura feita inteiramente à mão.',
                'preco' => 40.00,
                'categoria' => 'Pano de Prato',
                'quantidade' => 2
            ],
            [
                'nome' => 'Pano de Prato Sublimado',
                'descricao' => 'Pano de prato com estampa sublimada de alta definição, durável e com excelente absorção.',
                'preco' => 30.00,
                'categoria' => 'Pano de Prato',
                'quantidade' => 7
            ],
            [
                'nome' => 'Boneca de Tecido Pequena',
                'descricao' => 'Bonequinha de pano decorativa e antialérgica, costurada com carinho em retalhos selecionados.',
                'preco' => 70.00,
                'categoria' => 'Bonecas de Tecido',
                'quantidade' => 3
            ],
            [
                'nome' => 'Boneca de Tecido Grande',
                'descricao' => 'Boneca de pano de grande porte, ideal para decoração de quartos infantis ou colecionadores.',
                'preco' => 300.00,
                'categoria' => 'Bonecas de Tecido',
                'quantidade' => 1
            ],
            [
                'nome' => 'Camisa Estampada',
                'descricao' => 'Camiseta com estampas exclusivas inspiradas na cultura local e regional de Caxias.',
                'preco' => 40.00,
                'categoria' => 'Camisas Estampadas',
                'quantidade' => 6
            ],
            [
                'nome' => 'Conjunto de Supla c/ 6 Peças',
                'descricao' => 'Lindo conjunto de sousplat (descanso de prato) artesanal em tecido de ótima qualidade para mesa posta de 6 lugares.',
                'preco' => 120.00,
                'categoria' => 'Supla e Mesa Posta',
                'quantidade' => 2
            ],

            // --- 🥥 Artesanato em Fibras e Babaçu ---
            [
                'nome' => 'Galinha d’Angola de Babaçu',
                'descricao' => 'Peça decorativa esculpida artesanalmente a partir do coco de babaçu com detalhes pintados à mão.',
                'preco' => 10.00,
                'categoria' => 'Decoração e Lembrancinhas',
                'quantidade' => 7
            ],
            [
                'nome' => 'Pássaro de Coco',
                'descricao' => 'Escultura minuciosa de pássaro tropical regional feita a partir da casca do coco natural.',
                'preco' => 15.00,
                'categoria' => 'Esculturas de Coco',
                'quantidade' => 13
            ],
            [
                'nome' => 'Porquinho de Coco',
                'descricao' => 'Item decorativo divertido e simpático esculpido de forma criativa usando coco de babaçu natural.',
                'preco' => 10.00,
                'categoria' => 'Esculturas de Coco',
                'quantidade' => 3
            ],
            [
                'nome' => 'Porta Caneta Coruja',
                'descricao' => 'Porta-canetas funcional e decorativo esculpido em formato de coruja utilizando o coco de babaçu.',
                'preco' => 30.00,
                'categoria' => 'Porta Canetas',
                'quantidade' => 3
            ],
            [
                'nome' => 'Porta Moedas de Coco',
                'descricao' => 'Porta-moedas ecológico e resistente confeccionado artesanalmente a partir de fibras e cascas naturais.',
                'preco' => 15.00,
                'categoria' => 'Porta Moedas',
                'quantidade' => 6
            ],
            [
                'nome' => 'Cesta de Fibra Natural',
                'descricao' => 'Cesta organizadora trançada à mão por artesãs locais usando fibras naturais e sustentáveis.',
                'preco' => 35.00,
                'categoria' => 'Cestas e Trançados',
                'quantidade' => 6
            ],
            [
                'nome' => 'Bandeja de Fibra',
                'descricao' => 'Bandeja elegante e rústica com base firme trançada artesanalmente com fibras regionais.',
                'preco' => 35.00,
                'categoria' => 'Cestas e Trançados',
                'quantidade' => 3
            ],
            [
                'nome' => 'Bolsa de Palha',
                'descricao' => 'Bolsa de mão fashionista e resistente trançada à mão com palhas nobres da nossa região.',
                'preco' => 50.00,
                'categoria' => 'Cestas e Trançados',
                'quantidade' => 1
            ],

            // --- 💎 Bijuterias e Biojoias ---
            [
                'nome' => 'Chaveiro de Sementes e Palha',
                'descricao' => 'Chaveiro artesanal exclusivo montado com sementes nativas e acabamento em trançado de palha.',
                'preco' => 10.00,
                'categoria' => 'Bijuterias e Biojoias',
                'quantidade' => 20
            ],
            [
                'nome' => 'Colar de Biojoia de Coco e Babaçu',
                'descricao' => 'Colar elegante com pingentes geométricos feitos de casca polida de coco de babaçu e sementes locais.',
                'preco' => 10.00,
                'categoria' => 'Bijuterias e Biojoias',
                'quantidade' => 20
            ],

            // --- 🏺 Decoração ---
            [
                'nome' => 'Jarro de Sapucaia',
                'descricao' => 'Vaso decorativo rústico feito com a casca do fruto da sapucaia, perfeito para arranjos secos.',
                'preco' => 60.00,
                'categoria' => 'Jarros e Vasos',
                'quantidade' => 8
            ],
            [
                'nome' => 'Jarro de Vidro Ornamentado',
                'descricao' => 'Vaso de vidro decorado artesanalmente com trançados e aplicações de fibras naturais.',
                'preco' => 95.00,
                'categoria' => 'Jarros e Vasos',
                'quantidade' => 3
            ],

            // --- 🪵 Artesanato em Madeira ---
            [
                'nome' => 'Caixa Organizadora de Madeira',
                'descricao' => 'Caixa organizadora artesanal feita em madeira maciça com encaixes precisos e acabamento em verniz acetinado.',
                'preco' => 45.90,
                'categoria' => 'Artesanato em Madeira',
                'quantidade' => 5
            ],
            [
                'nome' => 'Porta-Retratos Rústico de Madeira',
                'descricao' => 'Porta-retratos artesanal esculpido em madeira de demolição, ideal para fotos 10x15cm.',
                'preco' => 35.00,
                'categoria' => 'Artesanato em Madeira',
                'quantidade' => 4
            ]
        ];

        foreach ($produtos as $p) {
            $catId = $getCategoriaId($p['categoria']);

            // Se for categoria principal (como Bijuterias que não tem subcategoria)
            if (!$catId) {
                $catId = CategoriasProdutos::where('nome_categoria', $p['categoria'])->value('id_categoria');
            }

            $produto = Produto::create([
                'nome' => $p['nome'],
                'descricao' => $p['descricao'],
                'preco' => $p['preco'],
                'id_categoria' => $catId,
                'id_artesan' => $artesanId,
                'is_approved' => true,
                'imagem' => null,
            ]);

            if ($produto) {
                $produto->estoque()->create([
                    'id_produto' => $produto->id_produto,
                    'quantidade' => $p['quantidade'],
                ]);
            }
        }
    }
}
