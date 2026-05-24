<header>
    <nav class="sticky top-0 z-50 py-2 transition-all duration-300"
         :class="scrolled ? 'bg-brand shadow-lg' : 'bg-brand shadow-sm'"
         x-data="{ open: false, scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 40 }, { passive: true })">
        <div class="w-full px-2 sm:px-4">
            <div class="flex items-center justify-between">
                <a class="flex items-center gap-1 sm:gap-2 no-underline" href="{{ route('home') }}">
                    <img src="{{ asset(config('association.logo')) }}" alt="Logo" class="h-8 sm:h-10" loading="lazy">
                    <span class="font-bold text-sm sm:text-lg whitespace-nowrap text-accent">{{ config('association.name') }}</span>
                </a>

                <button @click="open = !open" :aria-expanded="open"
                        class="lg:hidden p-2 text-accent hover:text-accent-hover focus:outline-none">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="hidden lg:flex lg:items-center lg:gap-2 xl:gap-4" id="navbarNav">
                    <ul class="flex flex-col lg:flex-row lg:items-center gap-1 lg:gap-2 xl:gap-4 ml-auto list-none m-0 p-0">
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('home') }}">Home</a></li>
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('sobrenos') }}">Sobre nós</a></li>
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('evento') }}">Eventos</a></li>
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('produtos') }}">Produtos</a></li>
                        <li><a class="block px-5 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg lg:hover:bg-brand-dark/30" href="{{ route('contato') }}">Contato</a></li>

                        @guest
                            <li class="lg:mt-0">
                                <a class="block text-center px-4 py-2 text-accent font-semibold no-underline rounded-full border border-accent/30 hover:bg-accent hover:text-brand transition" href="{{ route('login.form') }}">Entrar</a>
                            </li>
                        @endguest

                        @auth
                            @if(auth()->user()->isAdmin())
                                <li class="lg:mt-0 relative" x-data="{ adminOpen: false }">
                                    <a href="#" @click.prevent="adminOpen = !adminOpen" @click.away="adminOpen = false"
                                       class="flex items-center justify-center gap-2 px-3 py-2 rounded-full text-brand bg-accent font-semibold no-underline hover:bg-accent-hover transition">
                                        <i class="fas fa-user-shield"></i>
                                        <span class="font-bold hidden lg:inline">Admin</span>
                                        <i class="fas fa-cog"></i>
                                    </a>
                                    <ul x-show="adminOpen" x-transition
                                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 list-none p-2"
                                        style="display: none;">
                                        <li><span class="block px-3 py-2 text-sm font-bold text-brand text-center">Olá, {{ auth()->user()->name }}</span></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt w-5"></i> Dashboard Admin</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('produtos') }}"><i class="fas fa-box w-5"></i> Produtos</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('evento') }}"><i class="fas fa-calendar-alt w-5"></i> Eventos</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.artesao') }}"><i class="fas fa-users w-5"></i> Gerir Artesãos</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.usuarios') }}"><i class="fas fa-user-friends w-5"></i> Usuários</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.inscricoes') }}"><i class="fas fa-clipboard-list w-5"></i> Inscrições</a></li>
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('admin.settings') }}"><i class="fas fa-tools w-5"></i> Configurações</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 font-bold rounded-lg hover:bg-red-50">
                                                    <i class="fas fa-sign-out-alt w-5"></i> Sair
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <li class="lg:mt-0 relative" x-data="{ userOpen: false }">
                                    <a href="#" @click.prevent="userOpen = !userOpen" @click.away="userOpen = false"
                                       class="flex items-center justify-center gap-2 px-3 py-2 rounded-full text-brand bg-accent font-semibold no-underline hover:bg-accent-hover transition">
                                        <i class="fas fa-user"></i>
                                        <span class="font-bold hidden lg:inline">Menu</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </a>
                                    <ul x-show="userOpen" x-transition
                                        class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 list-none p-2"
                                        style="display: none;">
                                        <li><span class="block px-3 py-2 text-sm font-bold text-brand text-center">Olá, {{ auth()->user()->name }}</span></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        @if(auth()->user()->isArtisan())
                                            <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('artesan.dashboard') }}"><i class="fas fa-tachometer-alt w-5"></i> Dashboard Artesão</a></li>
                                            <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('produtos') }}"><i class="fas fa-box-open w-5"></i> Meus Produtos</a></li>
                                            <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('evento') }}"><i class="fas fa-calendar-alt w-5"></i> Meus Eventos</a></li>
                                        @endif
                                        <li><a class="flex items-center gap-2 px-3 py-2 text-sm text-brand rounded-lg hover:bg-gray-100 no-underline" href="{{ route('user.perfil') }}"><i class="fas fa-id-card w-5"></i> Meu Perfil</a></li>
                                        <li><hr class="my-1 border-gray-200"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 font-bold rounded-lg hover:bg-red-50">
                                                    <i class="fas fa-sign-out-alt w-5"></i> Sair
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

            <div x-show="open" x-transition class="lg:hidden mt-2 bg-brand-dark/30 rounded-xl p-4">
                <ul class="flex flex-col gap-1 list-none m-0 p-0">
                    <li><a class="block px-4 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('home') }}">Home</a></li>
                    <li><a class="block px-4 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('sobrenos') }}">Sobre nós</a></li>
                    <li><a class="block px-4 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('evento') }}">Eventos</a></li>
                    <li><a class="block px-4 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('produtos') }}">Produtos</a></li>
                    <li><a class="block px-4 py-2.5 text-accent font-semibold no-underline hover:text-accent-hover rounded-lg hover:bg-brand-dark/30" href="{{ route('contato') }}">Contato</a></li>

                    @guest
                        <li class="mt-2">
                            <a class="block text-center px-4 py-2 text-accent font-semibold no-underline rounded-full border border-accent/30 hover:bg-accent hover:text-brand transition" href="{{ route('login.form') }}">Entrar</a>
                        </li>
                    @endguest

                    @auth
                        <li class="mt-3 pt-3 border-t border-accent/20">
                            <span class="block text-sm text-accent/80 px-4 mb-1">Olá, {{ auth()->user()->name }}</span>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt w-5"></i> Dashboard</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.artesao') }}"><i class="fas fa-users w-5"></i> Gerir Artesãos</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.usuarios') }}"><i class="fas fa-user-friends w-5"></i> Usuários</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.inscricoes') }}"><i class="fas fa-clipboard-list w-5"></i> Inscrições</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('admin.settings') }}"><i class="fas fa-tools w-5"></i> Configurações</a></li>
                        @elseif(auth()->user()->isArtisan())
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('artesan.dashboard') }}"><i class="fas fa-tachometer-alt w-5"></i> Dashboard</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('produtos') }}"><i class="fas fa-box-open w-5"></i> Meus Produtos</a></li>
                            <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('evento') }}"><i class="fas fa-calendar-alt w-5"></i> Meus Eventos</a></li>
                        @endif
                        <li><a class="flex items-center gap-2 px-4 py-2 text-accent no-underline rounded-lg hover:bg-brand-dark/30" href="{{ route('user.perfil') }}"><i class="fas fa-id-card w-5"></i> Meu Perfil</a></li>
                        <li class="mt-2 pt-2 border-t border-accent/20">
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-red-300 font-bold rounded-lg hover:bg-brand-dark/30 text-left">
                                    <i class="fas fa-sign-out-alt w-5"></i> Sair
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</header>
