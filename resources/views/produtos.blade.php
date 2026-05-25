@extends('layouts.main')
@section('titulo', 'Produtos')

@auth
    @if(auth()->user()->isAdmin())
        @section('content')
        <div class="w-full px-4 py-5">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
                <x-icon name="arrow-left" class="w-3 h-3" /> Voltar ao Painel
            </a>
            <div class="flex items-center justify-between mb-4">
                <h1 class="font-bold mb-0 text-brand text-2xl">Gerenciar Produtos</h1>
                <a href="{{ route('produtos.create') }}" class="inline-flex items-center gap-2 text-white font-bold px-4 py-2 rounded-full shadow-sm bg-brand hover:bg-brand-dark transition no-underline">
                    <x-icon name="plus" class="w-4 h-4" /> Novo Produto
                </a>
            </div>

            <x-alert type="success" :message="session('success')" />

            @if($produtos->isEmpty())
                <div class="text-center py-5">
                    <x-icon name="box" class="w-12 h-12 text-gray-400 mb-3 block" />
                    <p class="text-gray-500 text-xl">Nenhum produto cadastrado.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-xl shadow-sm overflow-hidden">
                        <thead style="background-color: #7a2f1f; color: #F9F7D3;">
                            <tr>
                                <th class="p-3 text-left">Imagem</th>
                                <th class="p-3 text-left">Nome</th>
                                <th class="p-3 text-left">Artesão</th>
                                <th class="p-3 text-left">Categoria</th>
                                <th class="p-3 text-left">Preço</th>
                                <th class="p-3 text-left">Estoque</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($produtos as $produto)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3">
                                        <x-image :src="$produto->imagem" alt="$produto->nome" class="rounded shadow-sm w-11 h-11 object-cover" />
                                    </td>
                                    <td class="font-semibold p-3 text-brand-light">{{ $produto->nome }}</td>
                                    <td class="p-3">{{ $produto->artisan?->name ?? 'Admin' }}</td>
                                    <td class="p-3">{{ $produto->categoria?->nome_categoria }}</td>
                                    <td class="p-3 font-bold text-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                                    <td class="p-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ ($produto->estoque?->quantidade ?? 0) > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $produto->estoque?->quantidade ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        @if($produto->is_approved)
                                            <x-badge type="success">Ativo</x-badge>
                                        @else
                                            <x-badge type="pending">Pendente</x-badge>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('produtos.edit', $produto->id_produto) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm border border-yellow-400 text-yellow-600 rounded-lg hover:bg-yellow-400 hover:text-white transition no-underline">
                                                <x-icon name="pencil" class="w-4 h-4" /> Editar
                                            </a>
                                            <form action="{{ route('produtos.destroy', $produto->id_produto) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm border border-red-400 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition" onclick="confirmarExclusao(this)">
                                                    <x-icon name="trash" class="w-4 h-4" /> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-center">
                    {{ $produtos->links() }}
                </div>
            @endif
        </div>
        @endsection
    @elseif(auth()->user()->isArtisan())
        @section('content')
        <div class="max-w-7xl mx-auto px-4 py-5">
            <a href="{{ route('artesan.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
                <x-icon name="arrow-left" class="w-3 h-3" /> Voltar ao Painel
            </a>
            <div class="flex items-center justify-between mb-4">
                <h1 class="font-bold mb-0 text-brand text-2xl">Meus Produtos</h1>
                <a href="{{ route('artesan.produtos.criar') }}" class="inline-flex items-center gap-2 text-white font-bold px-4 py-2 rounded-full shadow-sm bg-brand hover:bg-brand-dark transition no-underline">
                    <x-icon name="plus" class="w-4 h-4" /> Propor Novo Produto
                </a>
            </div>

            <x-alert type="success" :message="session('success')" />

            @if($produtos->isEmpty())
                <div class="text-center py-5">
                    <x-icon name="box" class="w-12 h-12 text-gray-400 mb-3 block" />
                    <p class="text-gray-500 text-xl">Você ainda não cadastrou ou propôs produtos.</p>
                    <p class="text-gray-400">Clique no botão acima para propor o seu primeiro produto de artesanato!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($produtos as $produto)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden h-full flex flex-col">
                            <div class="overflow-hidden relative h-44">
                                <x-image :src="$produto->imagem" alt="$produto->nome" class="w-full h-full object-cover" />
                            </div>
                            <div class="p-3 flex flex-col flex-1">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h5 class="font-bold mb-0 truncate text-brand" title="{{ $produto->nome }}">{{ $produto->nome }}</h5>
                                    @if($produto->is_approved)
                                        <x-badge type="success">Ativo</x-badge>
                                    @else
                                        <x-badge type="pending">Pendente</x-badge>
                                    @endif
                                </div>
                                <p class="text-gray-500 text-sm mb-2">{{ $produto->categoria?->nome_categoria }}</p>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="font-bold text-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                    <span class="text-sm text-gray-500">Estoque: {{ $produto->estoque?->quantidade ?? 0 }}</span>
                                </div>
                                <div class="flex gap-2 mt-3 pt-2 border-t border-gray-200">
                                    <x-card-actions :edit-route="route('artesan.produtos.editar', $produto->id_produto)" :delete-route="route('artesan.produtos.deletar', $produto->id_produto)" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endsection
    @endif
@endauth

@if(auth()->guest() || (auth()->check() && !auth()->user()->isAdmin() && !auth()->user()->isArtisan()))

    @section('content')
    <div class="w-full px-3 py-2">
        <div class="max-w-7xl mx-auto">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-2">
                <x-icon name="arrow-left" class="w-3 h-3" /> Voltar
            </a>
            <div class="flex flex-col lg:flex-row gap-3 mb-2 items-center relative" style="z-index: 40;">
                <div class="flex-1 w-full lg:w-auto">
                    <div class="bg-white rounded-full shadow-sm p-1">
                        <form action="{{ route('produtos') }}" method="GET" class="flex w-full m-0">
                            @if(request('categoria'))
                                <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                            @endif
                            <div class="relative" id="cat-dropdown">
                                <button type="button" onclick="toggleCatDropdown()"
                                        class="flex items-center gap-2 px-4 py-3 text-sm font-bold text-brand rounded-l-full border-0 cursor-pointer" style="background-color: #f0eecf;">
                                    <x-icon name="filter" class="w-4 h-4" />
                                    <span class="hidden sm:inline font-bold">Categorias</span>
                                </button>
                                <ul id="cat-menu"
                                    class="absolute left-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 list-none p-2"
                                    style="display: none; min-width: 250px;">
                                    <li>
                                        <a class="block px-3 py-2 text-sm rounded-lg {{ !request('categoria') ? 'bg-gray-100 font-bold' : '' }} text-brand no-underline hover:bg-gray-100" href="{{ route('produtos', ['busca' => request('busca')]) }}">
                                            <x-icon name="grid" class="w-4 h-4 mr-2 text-gray-400" /> Todas as Categorias
                                        </a>
                                    </li>
                                    <li><hr class="my-1 border-gray-200"></li>
                                    @foreach($categorias as $categoria)
                                    <li>
                                        <a class="block px-3 py-2 text-sm rounded-lg {{ request('categoria') == $categoria->id_categoria ? 'bg-gray-100 font-bold' : '' }} text-gray-700 no-underline hover:bg-gray-100"
                                           href="{{ route('produtos', ['categoria' => $categoria->id_categoria, 'busca' => request('busca')]) }}">
                                            {{ $categoria->nome_categoria }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="text" name="busca"
                                   class="flex-1 border-0 px-4 py-3 text-base outline-none bg-transparent"
                                   placeholder="Buscar artesanatos..." value="{{ request('busca') }}">
                            <button type="submit" class="px-4 text-white rounded-r-full cursor-pointer border-0" style="background-color: #7a2f1f;">
                                <x-icon name="search" class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-sm text-gray-500 font-bold bg-white px-3 py-2 rounded-full shadow-sm whitespace-nowrap">
                        <x-icon name="box" class="w-4 h-4 mr-1" /> {{ $produtos->count() }} itens
                    </span>
                    <button class="text-white font-bold px-3 py-2 rounded-full shadow-sm relative cursor-pointer border-0" style="background-color: #7a2f1f;" onclick="abrirCarrinho()" title="Carrinho">
                        <x-icon name="cart" class="w-5 h-5" />
                        <span id="badge-carrinho" class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full" style="display: none;">0</span>
                    </button>
                </div>
            </div>

            @if($produtos->isEmpty())
                <div class="text-center py-5">
                    <x-icon name="search" class="w-12 h-12 text-gray-400 mb-3 block" />
                    <p class="text-gray-500 text-xl">Nenhum produto cadastrado nesta categoria.</p>
                    <a href="{{ route('produtos') }}" class="mt-2 inline-block px-4 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 no-underline">Ver tudo</a>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="produtos-grid">
                @foreach($produtos as $produto)
                <div class="bg-white h-full rounded-xl shadow-sm overflow-hidden produto-card cursor-pointer border-2 border-transparent hover:border-brand-light hover:-translate-y-2 hover:shadow-md transition-all"
                     data-id="{{ $produto->id_produto }}"
                     data-nome="{{ $produto->nome }}"
                     data-preco="{{ $produto->preco }}"
                     data-descricao="{{ $produto->descricao }}"
                     data-estoque="{{ $produto->estoque ? $produto->estoque->quantidade : 0 }}"
                     data-imagem="{{ $produto->imagem ? (str_starts_with($produto->imagem, 'imagens/') ? asset($produto->imagem) : asset('storage/' . $produto->imagem)) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e8dfd6%22 width=%22200%22 height=%22200%22/%3E%3C/svg%3E' }}">
                    <div class="relative overflow-hidden h-52" style="background: linear-gradient(135deg, #e8dfd6 0%, #f5e6d3 100%);">
                        <img src="{{ $produto->imagem ? (str_starts_with($produto->imagem, 'imagens/') ? asset($produto->imagem) : asset('storage/' . $produto->imagem)) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23e8dfd6%22 width=%22200%22 height=%22200%22/%3E%3C/svg%3E' }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" alt="{{ $produto->nome }}" loading="lazy">
                        @if($produto->estoque && $produto->estoque->quantidade <= 0)
                            <span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500 text-white">Esgotado</span>
                        @endif
                    </div>

                    <div class="p-3 flex flex-col">
                        <h3 class="text-sm font-bold mb-1 truncate text-brand" title="{{ $produto->nome }}">{{ $produto->nome }}</h3>
                        @if($produto->artisan)
                            <div class="mb-2 flex items-center gap-2">
                                @if($produto->artisan->artisanProfile && $produto->artisan->artisanProfile->profile_photo)
                                    <img src="{{ asset('storage/' . $produto->artisan->artisanProfile->profile_photo) }}" alt="Foto de {{ $produto->artisan->name }}" class="rounded-full w-5 h-5 object-cover border border-brand" loading="lazy">
                                @else
                                    <div class="rounded-full w-5 h-5 flex items-center justify-center text-white text-xs font-bold" style="background-color: #7a2f1f;">
                                        {{ substr($produto->artisan->name, 0, 1) }}
                                    </div>
                                @endif
                                <a href="{{ route('artesao.publico', $produto->artisan->id) }}" class="text-xs text-gray-500 no-underline hover:text-brand-light" title="Ver portfólio de {{ $produto->artisan->name }}">
                                    Feito por <span class="font-bold text-brand-light">{{ $produto->artisan->name }}</span>
                                </a>
                            </div>
                        @endif

                        <div class="flex items-center justify-between mt-auto">
                            <span class="font-bold text-price text-lg">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                            <div class="flex gap-1">
                                 <button class="min-w-[44px] min-h-[44px] flex items-center justify-center text-sm border border-brand-light text-brand-light rounded hover:bg-brand-light hover:text-white transition cursor-pointer" onclick="abrirDetalhes(this)" title="Ver Detalhes">
                                     <x-icon name="eye" class="w-4 h-4" />
                                 </button>
                                 <button class="min-w-[44px] min-h-[44px] flex items-center justify-center text-sm text-white rounded cursor-pointer border-0" onclick="adicionarRapido(this)" title="Adicionar" style="background-color: #7a2f1f;">
                                     <x-icon name="cart" class="w-4 h-4" />
                                 </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-center">
                {{ $produtos->links() }}
            </div>
        </div>
    </div>
    @endsection

    @section('modals')
    <div id="modal-detalhes" class="modal-overlay modal-lg" onclick="if(event.target===this)hideModal('modal-detalhes')">
        <div>
            <div class="flex items-center justify-between px-6 pt-6 pb-0">
                <h5 class="text-xl font-bold text-brand m-0" id="modal-nome"></h5>
                <button onclick="fecharModal()" class="text-3xl text-gray-400 hover:text-gray-600 leading-none bg-transparent border-0 cursor-pointer">&times;</button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl overflow-hidden shadow-sm h-48 sm:h-64 md:h-80" style="background-color: #f8f9fa;">
                        <img id="modal-img" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <div class="text-2xl font-bold text-price mb-3" id="modal-preco"></div>
                        <div class="mb-3" id="modal-estoque"></div>
                        <p class="text-gray-500 mb-4 flex-1" id="modal-descricao"></p>
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <label for="modal-quantidade" class="font-bold">Qtd:</label>
                                <input type="number" id="modal-quantidade" class="w-20 text-center border border-gray-300 rounded-lg px-3 py-2" value="1" min="1">
                            </div>
                            <button class="w-full text-white font-bold px-4 py-3 rounded-lg cursor-pointer border-0 text-lg" style="background-color: #7a2f1f;" onclick="adicionarAoCarrinho()">
                                <x-icon name="cart" class="w-5 h-5 mr-2" /> Adicionar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-carrinho" class="modal-overlay" onclick="if(event.target===this)hideModal('modal-carrinho')">
        <div>
            <div class="flex items-center justify-between px-6 pt-6 pb-0">
                <h5 class="text-xl font-bold text-brand m-0">Meu Carrinho</h5>
                <button onclick="fecharCarrinho()" class="text-3xl text-gray-400 hover:text-gray-600 leading-none bg-transparent border-0 cursor-pointer">&times;</button>
            </div>
            <div class="p-6" id="carrinho-conteudo"></div>
        </div>
    </div>


    @endsection

    @section('scripts')
    <script>
        function toggleCatDropdown() {
            const menu = document.getElementById('cat-menu');
            if (menu) menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(e) {
            const dd = document.getElementById('cat-dropdown');
            const menu = document.getElementById('cat-menu');
            if (dd && menu && !dd.contains(e.target)) menu.style.display = 'none';
        });
        window.Laravel = {
            auth: {{ auth()->check() ? 'true' : 'false' }},
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    <script src="{{ asset('js/cart.js') }}"></script>
    @endsection
@endif
