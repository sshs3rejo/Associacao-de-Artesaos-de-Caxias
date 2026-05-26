<header>
    <nav class="sticky top-0 z-50 py-2 bg-brand transition-all duration-300"
         id="navbar-root">
        <div class="w-full px-2 sm:px-4">
            <div class="flex items-center justify-between">
                <a class="flex items-center gap-1 sm:gap-2 no-underline" href="{{ route('home') }}">
                    <x-image src="{{ config('association.logo') }}" alt="Logo" class="h-8 sm:h-10" />
                    <span class="font-bold text-sm sm:text-lg text-accent whitespace-nowrap">{{ config('association.name') }}</span>
                </a>

                <button onclick="toggleMobileMenu()"
                         class="lg:hidden p-3 text-accent hover:text-accent-hover focus:outline-none cursor-pointer border-0 bg-transparent"
                        aria-label="Menu">
                    <x-icon name="menu" class="w-6 h-6" id="hamburger-icon" />
                    <x-icon name="x" class="w-6 h-6 hidden" id="close-icon" />
                </button>

                <div class="hidden lg:flex lg:items-center lg:gap-2 xl:gap-4" id="navbarNav">
                    <ul class="flex flex-col lg:flex-row lg:items-center gap-1 lg:gap-2 xl:gap-4 ml-auto list-none m-0 p-0">
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('home') }}">Home</a></li>
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('sobrenos') }}">Sobre n&oacute;s</a></li>
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('evento') }}">Eventos</a></li>
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('produtos') }}">Produtos</a></li>
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('contato') }}">Contato</a></li>

                        <li class="flex items-center">
                            <button class="text-white font-bold px-3 py-2 rounded-full relative cursor-pointer border-0 bg-transparent hover:bg-brand-dark/30 transition" onclick="abrirCarrinho()" title="Carrinho">
                                <x-icon name="cart" class="w-5 h-5" />
                                <span class="badge-carrinho absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full" style="display: none;">0</span>
                            </button>
                        </li>

                        @guest
                            <li class="lg:mt-0">
                                <a class="block text-center px-4 py-2 text-accent font-semibold no-underline rounded-full border border-accent/30 hover:bg-accent hover:text-brand transition" href="{{ route('login.form') }}">Entrar</a>
                            </li>
                        @endguest

                        @auth
                            @if(auth()->user()->isAdmin())
                                <li class="lg:mt-0 relative admin-dropdown-wrap">
                                    <a href="#" onclick="toggleDropdown('admin-dropdown-menu'); return false;"
                                       class="flex items-center justify-center gap-2 px-3 py-2 rounded-full text-brand bg-accent font-semibold no-underline hover:bg-accent-hover transition"
                                       id="admin-toggle" aria-label="Menu do Administrador">
                                        <x-icon name="user-shield" class="w-4 h-4" />
                                        <span class="font-bold hidden lg:inline">Admin</span>
                                        <x-icon name="cog" class="w-4 h-4" />
                                    </a>
                                    <ul id="admin-dropdown-menu" class="admin-dropdown absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 list-none p-2"
                                        style="display: none;">
                                        <li><span class="block px-3 py-2 text-sm font-bold text-brand text-center">Ol&aacute;, {{ auth()->user()->name }}</span></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.dashboard') }}"><x-icon name="dashboard" class="w-5 h-5" /> Dashboard Admin</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li><span class="block px-3 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gest&atilde;o</span></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.produtos.index') }}"><x-icon name="box" class="w-5 h-5" /> Produtos</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.eventos.index') }}"><x-icon name="calendar" class="w-5 h-5" /> Eventos</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.categorias.index') }}"><x-icon name="tags" class="w-5 h-5" /> Categorias</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.oficinas.index') }}"><x-icon name="chalkboard" class="w-5 h-5" /> Oficinas</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.instrutores.index') }}"><x-icon name="user-tie" class="w-5 h-5" /> Instrutores</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li><span class="block px-3 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pessoas</span></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.artesao') }}"><x-icon name="users" class="w-5 h-5" /> Artes&atilde;os</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.usuarios') }}"><x-icon name="user-friends" class="w-5 h-5" /> Usu&aacute;rios</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.clientes.index') }}"><x-icon name="address-book" class="w-5 h-5" /> Clientes</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li><span class="block px-3 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Vendas &amp; Compras</span></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.vendas.index') }}"><x-icon name="cart" class="w-5 h-5" /> Vendas</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.inscricoes') }}"><x-icon name="clipboard-list" class="w-5 h-5" /> Inscri&ccedil;&otilde;es</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.inscricoes-oficina.index') }}"><x-icon name="clipboard-check" class="w-5 h-5" /> Insc. Oficinas</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.compras-materia-prima.index') }}"><x-icon name="truck" class="w-5 h-5" /> Compras Mat.-Prima</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li><span class="block px-3 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Suprimentos</span></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.fornecedores.index') }}"><x-icon name="handshake" class="w-5 h-5" /> Fornecedores</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.materias-primas.index') }}"><x-icon name="cubes" class="w-5 h-5" /> Mat&eacute;rias-Primas</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li><span class="block px-3 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sistema</span></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.contatos.index') }}"><x-icon name="envelope" class="w-5 h-5" /> Contatos (submiss&otilde;es)</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.settings') }}"><x-icon name="tools" class="w-5 h-5" /> Configura&ccedil;&otilde;es</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.activity-log') }}"><x-icon name="history" class="w-5 h-5" /> Activity Log</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 font-bold rounded-lg hover:bg-red-50 cursor-pointer border-0 bg-transparent text-left">
                                                    <x-icon name="sign-out" class="w-5 h-5" /> Sair
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <li class="lg:mt-0 relative admin-dropdown-wrap">
                                    <a href="#" onclick="toggleDropdown('user-dropdown-menu'); return false;"
                                       class="flex items-center justify-center gap-2 px-3 py-2 rounded-full text-brand bg-accent font-semibold no-underline hover:bg-accent-hover transition"
                                       id="user-toggle" aria-label="Menu do Usuário">
                                        <x-icon name="user" class="w-4 h-4" />
                                        <span class="font-bold hidden lg:inline">Menu</span>
                                        <x-icon name="chevron-down" class="w-4 h-4" />
                                    </a>
                                    <ul id="user-dropdown-menu" class="admin-dropdown absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 list-none p-2"
                                        style="display: none;">
                                        <li><span class="block px-3 py-2 text-sm font-bold text-brand text-center">Ol&aacute;, {{ auth()->user()->name }}</span></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        @if(auth()->user()->isArtisan())
                                            <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('artesan.dashboard') }}"><x-icon name="dashboard" class="w-5 h-5" /> Dashboard Artes&atilde;o</a></li>
                                            <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('produtos') }}"><x-icon name="box-open" class="w-5 h-5" /> Meus Produtos</a></li>
                                            <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('evento') }}"><x-icon name="calendar" class="w-5 h-5" /> Meus Eventos</a></li>
                                        @endif
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('user.perfil') }}"><x-icon name="id-card" class="w-5 h-5" /> Meu Perfil</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 font-bold rounded-lg hover:bg-red-50 cursor-pointer border-0 bg-transparent text-left">
                                                    <x-icon name="sign-out" class="w-5 h-5" /> Sair
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>

            <div id="mobile-menu" class="lg:hidden mt-2 bg-brand-dark/30 rounded-xl p-4 hidden mobile-menu-scroll">
                <ul class="flex flex-col gap-1 list-none m-0 p-0">
                    <li><a class="block px-4 py-3 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('home') }}">Home</a></li>
                    <li><a class="block px-4 py-3 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('sobrenos') }}">Sobre n&oacute;s</a></li>
                    <li><a class="block px-4 py-3 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('evento') }}">Eventos</a></li>
                    <li><a class="block px-4 py-3 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('produtos') }}">Produtos</a></li>
                    <li><a class="block px-4 py-3 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('contato') }}">Contato</a></li>

                    <li>
                        <button class="flex items-center gap-2 w-full px-4 py-3 text-accent font-semibold no-underline rounded-lg hover:bg-brand-dark/30 cursor-pointer border-0 bg-transparent" onclick="abrirCarrinho()">
                            <x-icon name="cart" class="w-5 h-5" />
                            Carrinho
                            <span class="badge-carrinho inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full" style="display: none;">0</span>
                        </button>
                    </li>

                    @guest
                        <li class="mt-2">
                            <a class="block text-center px-4 py-2 text-accent font-semibold no-underline rounded-full border border-accent/30 hover:bg-accent hover:text-brand transition" href="{{ route('login.form') }}">Entrar</a>
                        </li>
                    @endguest

                    @auth
                        <li class="mt-3 pt-3 border-t border-accent/20">
                            <span class="block text-sm text-accent/80 px-4 mb-1">Ol&aacute;, {{ auth()->user()->name }}</span>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.dashboard') }}"><x-icon name="dashboard" class="w-5 h-5" /> Dashboard</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.produtos.index') }}"><x-icon name="box" class="w-5 h-5" /> Produtos</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.eventos.index') }}"><x-icon name="calendar" class="w-5 h-5" /> Eventos</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.categorias.index') }}"><i class="fas fa-tags w-5 h-5"></i> Categorias</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.oficinas.index') }}"><x-icon name="chalkboard" class="w-5 h-5" /> Oficinas</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.instrutores.index') }}"><x-icon name="user-tie" class="w-5 h-5" /> Instrutores</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.artesao') }}"><x-icon name="users" class="w-5 h-5" /> Artes&atilde;os</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.usuarios') }}"><x-icon name="user-friends" class="w-5 h-5" /> Usu&aacute;rios</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.clientes.index') }}"><x-icon name="address-book" class="w-5 h-5" /> Clientes</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.vendas.index') }}"><x-icon name="cart" class="w-5 h-5" /> Vendas</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.inscricoes') }}"><x-icon name="clipboard-list" class="w-5 h-5" /> Inscri&ccedil;&otilde;es</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.inscricoes-oficina.index') }}"><x-icon name="clipboard-check" class="w-5 h-5" /> Insc. Oficinas</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.compras-materia-prima.index') }}"><x-icon name="truck" class="w-5 h-5" /> Compras Mat.-Prima</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.fornecedores.index') }}"><x-icon name="handshake" class="w-5 h-5" /> Fornecedores</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.materias-primas.index') }}"><x-icon name="cubes" class="w-5 h-5" /> Mat&eacute;rias-Primas</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.contatos.index') }}"><x-icon name="envelope" class="w-5 h-5" /> Contatos (submiss&otilde;es)</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.settings') }}"><x-icon name="tools" class="w-5 h-5" /> Configura&ccedil;&otilde;es</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.activity-log') }}"><x-icon name="history" class="w-5 h-5" /> Activity Log</a></li>
                        @elseif(auth()->user()->isArtisan())
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('artesan.dashboard') }}"><x-icon name="dashboard" class="w-5 h-5" /> Dashboard</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('produtos') }}"><x-icon name="box-open" class="w-5 h-5" /> Meus Produtos</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('evento') }}"><x-icon name="calendar" class="w-5 h-5" /> Meus Eventos</a></li>
                        @endif
                        <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('user.perfil') }}"><x-icon name="id-card" class="w-5 h-5" /> Meu Perfil</a></li>
                        <li class="mt-2 pt-2 border-t border-accent/20">
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-red-300 font-bold rounded-lg hover:bg-brand-dark/30 text-left cursor-pointer border-0 bg-transparent">
                                    <x-icon name="sign-out" class="w-5 h-5" /> Sair
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>