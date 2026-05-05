@extends('layouts.main')
@section('titulo', 'Produtos')
@section('style')
<link rel="stylesheet" href="{{asset('css/styles-produtos.css')}}">
@endsection
    
@section('content')
    <div class="container-fluid px-3 py-4">
        <div class="row g-3">
            <!-- Sidebar / Filtros -->
            <aside class="col-xxl-2 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 sticky-top" style="background-color: #f0eecf; top: 80px; z-index: 10;">
                    <h3 class="h6 fw-bold mb-3" style="color: #7a2f1f;">Categorias</h3>
                    <ul class="nav flex-column gap-1">
                        <li class="nav-item">
                            <a class="nav-link rounded-3 px-3 py-2 small {{ request('categoria') ? 'text-dark' : 'active bg-brown text-white fw-bold shadow-sm' }}" 
                               href="{{ route('produtos') }}"
                               style="{{ request('categoria') ? '' : 'background-color: #7a2f1f;' }}">
                                Todas as Categorias
                            </a>
                        </li>
                        @foreach($categorias as $categoria)
                        <li class="nav-item">
                            <a class="nav-link rounded-3 px-3 py-2 small {{ request('categoria') == $categoria->id_categoria ? 'active bg-brown text-white fw-bold shadow-sm' : 'text-dark' }}" 
                               href="{{ route('produtos', ['categoria' => $categoria->id_categoria]) }}"
                               style="{{ request('categoria') == $categoria->id_categoria ? 'background-color: #7a2f1f;' : '' }}">
                                {{ $categoria->nome_categoria }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <!-- Grid de Produtos -->
            <div class="col-xxl-10 col-lg-9">
                <!-- Barra de busca e Resumo -->
                <div class="row g-3 mb-4 align-items-center">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm rounded-4 p-2 bg-white">
                            <form action="{{ route('produtos') }}" method="GET" class="d-flex gap-2">
                                @if(request('categoria'))
                                    <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                                @endif
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-0">
                                        <i class="fa fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="busca" class="form-control border-0 shadow-none"
                                           placeholder="Buscar artesanato..."
                                           value="{{ request('busca') }}">
                                </div>
                                <button type="submit" class="btn fw-bold px-4 text-white rounded-3" style="background-color: #7a2f1f;">
                                    Buscar
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="small text-muted fw-bold">
                            {{ $produtos->count() }} itens encontrados
                        </span>
                    </div>
                </div>

                @if($produtos->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted fs-5">Nenhum produto cadastrado nesta categoria.</p>
                        <a href="{{ route('produtos') }}" class="btn btn-outline-dark mt-2">Ver tudo</a>
                    </div>
                @endif

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-6 g-3" id="produtos-grid">
                    @foreach($produtos as $produto)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden produto-card" 
                             data-id="{{ $produto->id_produto }}"
                             data-nome="{{ $produto->nome }}"
                             data-preco="{{ $produto->preco }}"
                             data-descricao="{{ $produto->descricao }}"
                             data-estoque="{{ $produto->estoque ? $produto->estoque->quantidade : 0 }}"
                             data-imagem="{{ $produto->imagem ? asset('storage/imagens_produtos/' . $produto->imagem) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e8dfd6%22 width=%22200%22 height=%22200%22/%3E%3C/svg%3E' }}">
                            
                            <div class="position-relative overflow-hidden" style="height: 180px; background-color: #f8f9fa;">
                                <img src="{{ $produto->imagem ? asset('storage/imagens_produtos/' . $produto->imagem) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e8dfd6%22 width=%22200%22 height=%22200%22/%3E%3C/svg%3E' }}" 
                                     class="card-img-top w-100 h-100" alt="{{ $produto->nome }}" style="object-fit: cover; transition: transform 0.3s ease;">
                                @if($produto->estoque && $produto->estoque->quantidade <= 0)
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger">Esgotado</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column p-3">
                                <h3 class="h6 fw-bold mb-1 text-truncate" title="{{ $produto->nome }}" style="color: #7a2f1f;">{{ $produto->nome }}</h3>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="fw-bold mb-0" style="color: #c85a3a;">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-brown p-1 d-flex align-items-center justify-content-center" onclick="abrirDetalhes(this)" style="width: 28px; height: 28px;" title="Ver Detalhes">
                                            <i class="fa fa-eye" style="font-size: 0.8rem;"></i>
                                        </button>
                                        <button class="btn btn-sm btn-brown p-1 d-flex align-items-center justify-content-center text-white" onclick="adicionarRapido(this)" style="width: 28px; height: 28px; background-color: #7a2f1f;" title="Adicionar">
                                            <i class="fa fa-shopping-cart" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL DA GALERIA (Bootstrap Modal) -->
    <div class="modal fade" id="modal-detalhes" tabindex="-1" aria-labelledby="modal-nome" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="modal-nome" style="color: #7a2f1f;"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="fecharModal()"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="rounded-4 overflow-hidden shadow-sm" style="height: 350px; background-color: #f8f9fa;">
                                <img id="modal-img" src="" alt="" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column">
                            <div class="modal-preco h3 fw-bold mb-3" id="modal-preco" style="color: #c85a3a;"></div>
                            <div class="modal-estoque mb-3" id="modal-estoque"></div>
                            <p id="modal-descricao" class="text-muted mb-4"></p>
                            
                            <div class="mt-auto">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <label for="modal-quantidade" class="fw-bold">Qtd:</label>
                                    <input type="number" id="modal-quantidade" class="form-control text-center" value="1" min="1" style="width: 80px;">
                                </div>
                                <div class="d-flex gap-3">
                                    <button class="btn btn-lg w-100 fw-bold text-white" style="background-color: #7a2f1f;" onclick="adicionarAoCarrinho()">
                                        <i class="fa fa-shopping-cart me-2"></i> Adicionar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carrinho Modal -->
    <div class="modal fade" id="modal-carrinho" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" style="color: #7a2f1f;">Meu Carrinho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="fecharCarrinho()"></button>
                </div>
                <div class="modal-body p-4" id="carrinho-conteudo">
                    <!-- Dinâmico -->
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    {{-- O script-produtos.js ainda é necessário, mas vamos modificá-lo --}}
    <script src="{{asset('js/script-produtos.js')}}"></script>

    {{-- Esse é o novo JS que vai "corrigir" o script antigo --}}
    <script>
        // ========================================
        // DADOS DOS PRODUTOS
        // ========================================
        // O array 'const produtos' que estava em script-produtos.js NÃO É MAIS USADO.
        // Os dados agora são lidos do HTML.

        // Apagamos as funções que não são mais necessárias
        window.renderizarProdutos = function() {};
        window.filtrarCategoria = function() {};

        // Sobrescrevemos as funções que precisam ler do HTML
        
        /**
         * Abre o modal com detalhes do produto
         * @param {HTMLElement} botao O botão "Ver" que foi clicado.
         */
        window.abrirDetalhes = function(botao) {
            const card = botao.closest(".produto-card");

            // Lê os dados direto do HTML (data-attributes)
            window.produtoAtual = {
                id: card.dataset.id,
                nome: card.dataset.nome,
                preco: parseFloat(card.dataset.preco),
                descricao: card.dataset.descricao,
                imagem: card.dataset.imagem,
                estoque: parseInt(card.dataset.estoque) || 0,
            };

            // Preencher dados do modal (o resto da função original funciona)
            document.getElementById("modal-nome").textContent = window.produtoAtual.nome;
            document.getElementById("modal-preco").textContent = formatarPreco(window.produtoAtual.preco);
            document.getElementById("modal-descricao").textContent = window.produtoAtual.descricao;
            document.getElementById("modal-img").src = window.produtoAtual.imagem;
            document.getElementById("modal-quantidade").value = 1;
            document.getElementById("modal-quantidade").max = window.produtoAtual.estoque;

            const estoqueDiv = document.getElementById("modal-estoque");
            if (window.produtoAtual.estoque > 0) {
                estoqueDiv.innerHTML = `<span class="em-estoque">Em estoque (${window.produtoAtual.estoque} disponível)</span>`;
            } else {
                estoqueDiv.innerHTML = `<span class="fora-estoque">Fora de estoque</span>`;
            }

            document.getElementById("modal-detalhes").style.display = "block";
        }

        /**
         * Adiciona produto ao carrinho (botão rápido)
         * @param {HTMLElement} botao O botão "Carrinho" que foi clicado.
         */
        window.adicionarRapido = function(botao) {
            const card = botao.closest(".produto-card");

            const produto = {
                id: card.dataset.id,
                nome: card.dataset.nome,
                preco: parseFloat(card.dataset.preco),
                imagem: card.dataset.imagem,
            };

            const itemExistente = window.carrinho.find((item) => item.id === produto.id);

            if (itemExistente) {
                itemExistente.quantidade += 1;
            } else {
                window.carrinho.push({
                    id: produto.id,
                    nome: produto.nome,
                    preco: produto.preco,
                    quantidade: 1,
                    imagem: produto.imagem,
                });
            }

            window.atualizarBadge();
        }

        // Remove o 'DOMContentLoaded' antigo que chamava renderizarProdutos()
        document.addEventListener("DOMContentLoaded", function () {
            // O 'renderizarProdutos()' não é mais necessário aqui.
            // O Blade/PHP já fez isso.
        });

    </script>
@endsection