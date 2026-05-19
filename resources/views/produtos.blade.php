@extends('layouts.main')
@section('titulo', 'Produtos')

@auth
    @if(auth()->user()->isAdmin())
        @section('content')
        <div class="container-fluid px-4 py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold mb-0" style="color: #7a2f1f;">Gerenciar Produtos</h1>
                <a href="{{ route('produtos.create') }}" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background-color: #7a2f1f;">
                    <i class="bi bi-plus-lg me-2"></i> Novo Produto
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($produtos->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted fs-5">Nenhum produto cadastrado.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle bg-white rounded-4 shadow-sm overflow-hidden">
                        <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                            <tr>
                                <th class="p-3">Imagem</th>
                                <th class="p-3">Nome</th>
                                <th class="p-3">Artesão</th>
                                <th class="p-3">Categoria</th>
                                <th class="p-3">Preço</th>
                                <th class="p-3">Estoque</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produtos as $produto)
                                <tr>
                                    <td class="p-3">
                                        <img src="{{ $produto->imagem ? asset('storage/' . $produto->imagem) : config('association.placeholder') }}"
                                             alt="{{ $produto->nome }}" class="rounded shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                    </td>
                                    <td class="fw-semibold p-3" style="color: #8b5a3c;">{{ $produto->nome }}</td>
                                    <td class="p-3">{{ $produto->artisan?->name ?? 'Admin' }}</td>
                                    <td class="p-3">{{ $produto->categoria?->nome_categoria ?? '-' }}</td>
                                    <td class="p-3 fw-bold text-success">R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                                    <td class="p-3">{{ $produto->estoque?->quantidade ?? 0 }}</td>
                                    <td class="p-3">
                                        @if($produto->is_approved)
                                            <span class="badge bg-success">Aprovado</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('produtos.edit', $produto->id_produto) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <form action="{{ route('produtos.destroy', $produto->id_produto) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Remover produto {{ $produto->nome }} permanentemente?')">
                                                    <i class="bi bi-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $produtos->links() }}
                </div>
            @endif
        </div>
        @endsection

    @elseif(auth()->user()->isArtisan())
        @section('content')
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold mb-0" style="color: #7a2f1f;">Meus Produtos</h1>
                <a href="{{ route('artesan.produtos.criar') }}" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background-color: #7a2f1f;">
                    <i class="bi bi-plus-lg me-2"></i> Propor Novo Produto
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($produtos->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted fs-5">Você ainda não cadastrou ou propôs produtos.</p>
                    <p class="text-muted">Clique no botão acima para propor o seu primeiro produto de artesanato!</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($produtos as $produto)
                        <div class="col">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                <div class="overflow-hidden position-relative" style="height: 180px;">
                                    <img src="{{ $produto->imagem ? asset('storage/' . $produto->imagem) : config('association.placeholder') }}"
                                         class="card-img-top w-100 h-100" alt="{{ $produto->nome }}" style="object-fit: cover;">
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                        <h5 class="fw-bold mb-0 text-truncate" style="color: #7a2f1f;" title="{{ $produto->nome }}">{{ $produto->nome }}</h5>
                                        @if($produto->is_approved)
                                            <span class="badge bg-success" style="font-size: 0.7rem;">Ativo</span>
                                        @else
                                            <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Pendente</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-2">{{ $produto->categoria?->nome_categoria }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: #c85a3a;">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                        <span class="small text-muted">Estoque: {{ $produto->estoque?->quantidade ?? 0 }}</span>
                                    </div>
                                    <div class="d-flex gap-2 mt-3 pt-2 border-top">
                                        <a href="{{ route('artesan.produtos.editar', $produto->id_produto) }}" class="btn btn-sm btn-outline-primary flex-fill d-flex align-items-center justify-content-center gap-1" style="border-radius: 6px;">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <form action="{{ route('artesan.produtos.deletar', $produto->id_produto) }}" method="POST" class="flex-fill m-0 p-0" onsubmit="return confirm('Tem certeza que deseja remover este produto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-1" style="border-radius: 6px;">
                                                <i class="bi bi-trash"></i> Remover
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endsection

    @else
        @section('style')
        <link rel="stylesheet" href="{{asset('css/styles-produtos.css')}}">
        @endsection

        @section('content')
        <div class="container-fluid px-3 py-2">
            <div class="row g-3">
                <div class="col-12">
                    <div class="row g-3 mb-2 align-items-center position-relative" style="z-index: 1050;">
                        <div class="col-lg-9 col-xl-10">
                            <div class="card border-0 shadow-sm rounded-pill p-1 bg-white">
                                <form action="{{ route('produtos') }}" method="GET" class="d-flex w-100 m-0">
                                    @if(request('categoria'))
                                        <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                                    @endif
                                    <div class="input-group input-group-lg border-0 bg-transparent">
                                        <button class="btn dropdown-toggle d-flex align-items-center gap-2 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #f0eecf; color: #7a2f1f; border-top-left-radius: 50rem; border-bottom-left-radius: 50rem; padding-left: 1rem; padding-right: 1rem; font-size: 0.95rem;">
                                            <i class="fa fa-filter"></i>
                                            <span class="d-none d-sm-inline fw-bold">Categorias</span>
                                        </button>
                                        <ul class="dropdown-menu shadow-lg border-0 mt-2 rounded-4 p-2" style="min-width: 250px; z-index: 1060; animation: fadeInPage 0.2s ease;">
                                            <li>
                                                <a class="dropdown-item rounded-3 py-2 mb-1 {{ !request('categoria') ? 'bg-light fw-bold' : '' }}" href="{{ route('produtos', ['busca' => request('busca')]) }}" style="color: #7a2f1f;">
                                                    <i class="bi bi-grid-fill me-2 opacity-50"></i> Todas as Categorias
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider opacity-10"></li>
                                            @foreach($categorias as $categoria)
                                            <li>
                                                <a class="dropdown-item rounded-3 py-2 {{ request('categoria') == $categoria->id_categoria ? 'bg-light fw-bold' : '' }}" 
                                                   href="{{ route('produtos', ['categoria' => $categoria->id_categoria, 'busca' => request('busca')]) }}" 
                                                   style="color: #333;">
                                                    {{ $categoria->nome_categoria }}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <input type="text" name="busca" class="form-control border-0 shadow-none bg-transparent fs-6 ps-4"
                                               placeholder="Buscar artesanatos..." value="{{ request('busca') }}">
                                        <button type="submit" class="btn px-4 text-white" style="background-color: #7a2f1f; border-top-right-radius: 50rem; border-bottom-right-radius: 50rem;">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xl-2 d-flex justify-content-end gap-2 mt-3 mt-lg-0">
                            <span class="small text-muted fw-bold bg-white px-3 py-2 rounded-pill shadow-sm text-nowrap">
                                <i class="bi bi-box-seam me-1"></i> {{ $produtos->count() }} itens
                            </span>
                            <button class="btn text-white fw-bold rounded-pill px-3 shadow-sm position-relative" style="background-color: #7a2f1f;" onclick="abrirCarrinho()" title="Carrinho">
                                <i class="fa fa-shopping-cart"></i>
                                <span id="badge-carrinho" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; display: none;">0</span>
                            </button>
                        </div>
                    </div>

                    @if($produtos->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
                            <p class="text-muted fs-5">Nenhum produto cadastrado nesta categoria.</p>
                            <a href="{{ route('produtos') }}" class="btn btn-outline-dark mt-2">Ver tudo</a>
                        </div>
                    @endif

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-4" id="produtos-grid">
                        @foreach($produtos as $produto)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden produto-card" 
                                 data-id="{{ $produto->id_produto }}"
                                 data-nome="{{ $produto->nome }}"
                                 data-preco="{{ $produto->preco }}"
                                 data-descricao="{{ $produto->descricao }}"
                                 data-estoque="{{ $produto->estoque ? $produto->estoque->quantidade : 0 }}"
                                 data-imagem="{{ $produto->imagem ? (str_starts_with($produto->imagem, 'imagens/') ? asset($produto->imagem) : asset('storage/' . $produto->imagem)) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e8dfd6%22 width=%22200%22 height=%22200%22/%3E%3C/svg%3E' }}">
                                <div class="position-relative overflow-hidden" style="height: 210px; background-color: #f8f9fa;">
                                    <img src="{{ $produto->imagem ? (str_starts_with($produto->imagem, 'imagens/') ? asset($produto->imagem) : asset('storage/' . $produto->imagem)) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e8dfd6%22 width=%22200%22 height=%22200%22/%3E%3C/svg%3E' }}" 
                                         class="card-img-top w-100 h-100" alt="{{ $produto->nome }}" style="object-fit: cover; transition: transform 0.3s ease;">
                                    @if($produto->estoque && $produto->estoque->quantidade <= 0)
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-danger">Esgotado</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body d-flex flex-column p-3">
                                    <h3 class="h6 fw-bold mb-1 text-truncate" title="{{ $produto->nome }}" style="color: #7a2f1f;">{{ $produto->nome }}</h3>
                                    @if($produto->artisan)
                                        <div class="mb-2 d-flex align-items-center gap-2">
                                            @if($produto->artisan->artisanProfile && $produto->artisan->artisanProfile->profile_photo)
                                                <img src="{{ asset('storage/' . $produto->artisan->artisanProfile->profile_photo) }}" alt="Foto de {{ $produto->artisan->name }}" class="rounded-circle" style="width: 20px; height: 20px; object-fit: cover; border: 1px solid #7a2f1f;">
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 20px; height: 20px; font-size: 0.65rem; background-color: #7a2f1f; font-weight: bold;">
                                                    {{ substr($produto->artisan->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <a href="{{ route('artesao.publico', $produto->artisan->id) }}" class="text-decoration-none small text-muted hover-brown" style="font-size: 0.8rem;" title="Ver portfólio de {{ $produto->artisan->name }}">
                                                Feito por <span class="fw-bold" style="color: #8b5a3c;">{{ $produto->artisan->name }}</span>
                                            </a>
                                        </div>
                                    @endif

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
        @endsection

        @section('modals')
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

        <div class="modal fade" id="modal-carrinho" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" style="color: #7a2f1f;">Meu Carrinho</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="fecharCarrinho()"></button>
                    </div>
                    <div class="modal-body p-4" id="carrinho-conteudo">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-guest-checkout" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" style="color: #7a2f1f;">Finalizar Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted mb-3">Informe seus dados para finalizar o pedido:</p>
                        <div class="mb-3">
                            <label for="guest_name" class="form-label fw-semibold">Nome Completo</label>
                            <input type="text" class="form-control" id="guest_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="guest_email" class="form-label fw-semibold">E-mail</label>
                            <input type="email" class="form-control" id="guest_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="guest_phone" class="form-label fw-semibold">Telefone (opcional)</label>
                            <input type="text" class="form-control" id="guest_phone" placeholder="(99) 99999-9999">
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn flex-fill text-white fw-bold" style="background-color: #7a2f1f;" onclick="enviarCheckoutGuest()">
                                <i class="fa fa-check-circle me-1"></i> Confirmar Pedido
                            </button>
                            <button class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('scripts')
        <script>
            window.Laravel = {
                auth: {{ auth()->check() ? 'true' : 'false' }},
                checkoutUrl: '{{ route("checkout.store") }}',
                csrfToken: '{{ csrf_token() }}'
            };
        </script>
        <script src="{{ asset('js/cart.js') }}"></script>
        @endsection
    @endif
@else
    @section('style')
    <link rel="stylesheet" href="{{asset('css/styles-produtos.css')}}">
    @endsection

    @section('content')
    <div class="container-fluid px-3 py-2">
        <div class="row g-3">
            <div class="col-12">
                <div class="row g-3 mb-2 align-items-center position-relative" style="z-index: 1050;">
                    <div class="col-lg-9 col-xl-10">
                        <div class="card border-0 shadow-sm rounded-pill p-1 bg-white">
                            <form action="{{ route('produtos') }}" method="GET" class="d-flex w-100 m-0">
                                @if(request('categoria'))
                                    <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                                @endif
                                <div class="input-group input-group-lg border-0 bg-transparent">
                                    <button class="btn dropdown-toggle d-flex align-items-center gap-2 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #f0eecf; color: #7a2f1f; border-top-left-radius: 50rem; border-bottom-left-radius: 50rem; padding-left: 1rem; padding-right: 1rem; font-size: 0.95rem;">
                                        <i class="fa fa-filter"></i>
                                        <span class="d-none d-sm-inline fw-bold">Categorias</span>
                                    </button>
                                    <ul class="dropdown-menu shadow-lg border-0 mt-2 rounded-4 p-2" style="min-width: 250px; z-index: 1060; animation: fadeInPage 0.2s ease;">
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2 mb-1 {{ !request('categoria') ? 'bg-light fw-bold' : '' }}" href="{{ route('produtos', ['busca' => request('busca')]) }}" style="color: #7a2f1f;">
                                                <i class="bi bi-grid-fill me-2 opacity-50"></i> Todas as Categorias
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider opacity-10"></li>
                                        @foreach($categorias as $categoria)
                                        <li>
                                            <a class="dropdown-item rounded-3 py-2 {{ request('categoria') == $categoria->id_categoria ? 'bg-light fw-bold' : '' }}" 
                                               href="{{ route('produtos', ['categoria' => $categoria->id_categoria, 'busca' => request('busca')]) }}" 
                                               style="color: #333;">
                                                {{ $categoria->nome_categoria }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <input type="text" name="busca" class="form-control border-0 shadow-none bg-transparent fs-6 ps-4"
                                           placeholder="Buscar artesanatos..." value="{{ request('busca') }}">
                                    <button type="submit" class="btn px-4 text-white" style="background-color: #7a2f1f; border-top-right-radius: 50rem; border-bottom-right-radius: 50rem;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-2 d-flex justify-content-end gap-2 mt-3 mt-lg-0">
                        <span class="small text-muted fw-bold bg-white px-3 py-2 rounded-pill shadow-sm text-nowrap">
                            <i class="bi bi-box-seam me-1"></i> {{ $produtos->count() }} itens
                        </span>
                        <button class="btn text-white fw-bold rounded-pill px-3 shadow-sm position-relative" style="background-color: #7a2f1f;" onclick="abrirCarrinho()" title="Carrinho">
                            <i class="fa fa-shopping-cart"></i>
                            <span id="badge-carrinho" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; display: none;">0</span>
                        </button>
                    </div>
                </div>

                @if($produtos->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
                        <p class="text-muted fs-5">Nenhum produto cadastrado nesta categoria.</p>
                        <a href="{{ route('produtos') }}" class="btn btn-outline-dark mt-2">Ver tudo</a>
                    </div>
                @endif

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-4" id="produtos-grid">
                    @foreach($produtos as $produto)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden produto-card" 
                             data-id="{{ $produto->id_produto }}"
                             data-nome="{{ $produto->nome }}"
                             data-preco="{{ $produto->preco }}"
                             data-descricao="{{ $produto->descricao }}"
                             data-estoque="{{ $produto->estoque ? $produto->estoque->quantidade : 0 }}"
                             data-imagem="{{ $produto->imagem ? (str_starts_with($produto->imagem, 'imagens/') ? asset($produto->imagem) : asset('storage/' . $produto->imagem)) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e8dfd6%22 width=%22200%22 height=%22200%22/%3E%3C/svg%3E' }}">
                            <div class="position-relative overflow-hidden" style="height: 210px; background-color: #f8f9fa;">
                                <img src="{{ $produto->imagem ? (str_starts_with($produto->imagem, 'imagens/') ? asset($produto->imagem) : asset('storage/' . $produto->imagem)) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e8dfd6%22 width=%22200%22 height=%22200%22/%3E%3C/svg%3E' }}" 
                                     class="card-img-top w-100 h-100" alt="{{ $produto->nome }}" style="object-fit: cover; transition: transform 0.3s ease;">
                                @if($produto->estoque && $produto->estoque->quantidade <= 0)
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger">Esgotado</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column p-3">
                                <h3 class="h6 fw-bold mb-1 text-truncate" title="{{ $produto->nome }}" style="color: #7a2f1f;">{{ $produto->nome }}</h3>
                                @if($produto->artisan)
                                    <div class="mb-2 d-flex align-items-center gap-2">
                                        @if($produto->artisan->artisanProfile && $produto->artisan->artisanProfile->profile_photo)
                                            <img src="{{ asset('storage/' . $produto->artisan->artisanProfile->profile_photo) }}" alt="Foto de {{ $produto->artisan->name }}" class="rounded-circle" style="width: 20px; height: 20px; object-fit: cover; border: 1px solid #7a2f1f;">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 20px; height: 20px; font-size: 0.65rem; background-color: #7a2f1f; font-weight: bold;">
                                                {{ substr($produto->artisan->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <a href="{{ route('artesao.publico', $produto->artisan->id) }}" class="text-decoration-none small text-muted hover-brown" style="font-size: 0.8rem;" title="Ver portfólio de {{ $produto->artisan->name }}">
                                            Feito por <span class="fw-bold" style="color: #8b5a3c;">{{ $produto->artisan->name }}</span>
                                        </a>
                                    </div>
                                @endif

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
    @endsection

    @section('modals')
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

    <div class="modal fade" id="modal-carrinho" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" style="color: #7a2f1f;">Meu Carrinho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="fecharCarrinho()"></button>
                </div>
                <div class="modal-body p-4" id="carrinho-conteudo">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-guest-checkout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" style="color: #7a2f1f;">Finalizar Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-3">Informe seus dados para finalizar o pedido:</p>
                    <div class="mb-3">
                        <label for="guest_name" class="form-label fw-semibold">Nome Completo</label>
                        <input type="text" class="form-control" id="guest_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="guest_email" class="form-label fw-semibold">E-mail</label>
                        <input type="email" class="form-control" id="guest_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="guest_phone" class="form-label fw-semibold">Telefone (opcional)</label>
                        <input type="text" class="form-control" id="guest_phone" placeholder="(99) 99999-9999">
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn flex-fill text-white fw-bold" style="background-color: #7a2f1f;" onclick="enviarCheckoutGuest()">
                            <i class="fa fa-check-circle me-1"></i> Confirmar Pedido
                        </button>
                        <button class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        window.Laravel = {
            auth: {{ auth()->check() ? 'true' : 'false' }},
            checkoutUrl: '{{ route("checkout.store") }}',
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <script src="{{ asset('js/cart.js') }}"></script>
    @endsection
@endauth