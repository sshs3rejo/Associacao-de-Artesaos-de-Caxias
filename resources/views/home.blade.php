@extends('layouts.main')

@section('titulo', config('association.name_short') . ' - Home')

@section('content')
    <section class="hero-section py-5 flex items-center min-h-[50vh] md:min-h-[85vh]" style="background: linear-gradient(rgba(249, 247, 211, 0.85), rgba(249, 247, 211, 0.85)), url('{{ asset('imagens/artesanato_alunos/back-logo.webp') }}') no-repeat center/contain;">
        <div class="max-w-7xl mx-auto px-4 text-center fade-in">
            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-brand leading-tight">Aqui, o simples ganha forma, o barro respira, a palha canta e as mãos viram poesia.</h1>
            <p class="text-lg md:text-xl text-gray-500 mb-6 max-w-3xl mx-auto leading-relaxed">A Associação dos Artesãos de Caxias é um espaço onde a arte popular se encontra com o empreendedorismo criativo. Nosso propósito é conectar quem cria com quem valoriza o feito à mão.</p>

            <div class="flex flex-wrap justify-center gap-3" style="position: relative; z-index: 1;">
                <a href="{{ route('sobrenos') }}" class="btn-modern text-accent shadow-md hover:shadow-lg" style="background-color: #7a2f1f;">
                    <span>Conhecer a Associação</span>
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
                <a href="{{ route('produtos') }}" class="btn-modern text-accent shadow-md hover:shadow-lg" style="background-color: #7a2f1f;">
                    Ver Produtos
                </a>
            </div>
        </div>
    </section>

    <section class="py-5 md:py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-5">
                <h2 class="text-3xl md:text-4xl font-bold text-brand mb-2">Eventos e Atividades</h2>
                <p class="text-gray-500">Confira os próximos eventos e oficinas da associação</p>
            </div>

            @if($eventos->isEmpty())
                <div class="text-center py-5">
                    <p class="text-gray-500 text-lg">Nenhum evento futuro cadastrado no momento. Volte em breve!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($eventos as $evento)
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden flex flex-col card-hover">
                            @if($evento->imagem)
                                <div class="card-img-hover h-48">
                                    <x-image src="{{ $evento->imagem }}" alt="{{ $evento->nome }}" class="w-full h-full object-cover" />
                                </div>
                            @endif
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="text-lg font-bold text-brand mb-1">{{ $evento->nome }}</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    <x-icon name="calendar" class="w-4 h-4 mr-2" />
                                    {{ $evento->data_inicio ? $evento->data_inicio->format('d/m/Y H:i') : 'Data a definir' }}
                                </p>
                                <div class="mt-auto">
                                    <a href="{{ route('eventos.show', $evento->id_evento) }}" class="btn-modern w-full text-accent" style="background-color: #7a2f1f;">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="text-center mt-6">
                <a href="{{ route('evento') }}" class="btn-modern border-2 border-brand text-brand hover:bg-brand hover:text-accent">
                    Ver Todos os Eventos
                </a>
            </div>
        </div>
    </section>

    <section class="py-5 md:py-6" style="background-color: #f0eecf;">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-5">
                <h2 class="text-3xl md:text-4xl font-bold text-brand mb-2">Nossos Artesanatos</h2>
                <p class="text-gray-500">Nossa loja colaborativa é uma vitrine do talento de Caxias</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="artesanatos-gallery">
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('produtos') }}" class="btn-modern text-accent" style="background-color: #7a2f1f;">
                    Ver Todos os Produtos
                </a>
            </div>
        </div>
    </section>

    <div id="galleryOverlay" class="fixed inset-0 bg-black/60 z-[998] hidden" style="backdrop-filter: blur(4px);"></div>
    <div id="galleryModal" class="fixed inset-0 z-[999] hidden items-center justify-center p-4" style="display:none;">
        <div class="bg-accent rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 md:p-8 animate-[fadeIn_0.3s_ease-out]">
            <div class="flex items-center justify-between mb-4">
                <h5 id="galleryModalLabel" class="text-xl font-bold text-brand m-0">Título</h5>
                <button id="galleryCloseBtn" class="text-3xl text-brand hover:opacity-70 transition cursor-pointer bg-transparent border-0 leading-none">&times;</button>
            </div>
            <div class="text-center">
                <img id="modalImage" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="" class="max-w-full max-h-[60vh] rounded-xl shadow-md mb-4 mx-auto">
                <p id="modalDescription" class="text-gray-600 text-base">Descrição</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const galleryItems = [
                { src: '{{ asset('imagens/artesanato_alunos/artesanato1.webp') }}', alt: 'Cesta de palha artesanal', title: 'Cesta de Palha', description: 'Cesta tecida à mão com fibras naturais, ideal para decoração ou uso diário.' },
                { src: '{{ asset('imagens/artesanato_alunos/artesanato2.webp') }}', alt: 'Escultura de madeira rústica', title: 'Escultura em Madeira', description: 'Obra de arte entalhada em madeira rústica, representando a fauna local.' },
                { src: '{{ asset('imagens/artesanato_alunos/artesanato3.webp') }}', alt: 'Cerâmica pintada à mão', title: 'Vaso de Cerâmica', description: 'Vaso de cerâmica com pintura manual, um toque de arte para seu lar.' },
                { src: '{{ asset('imagens/artesanato_alunos/artesanato4.webp') }}', alt: 'Bolsa de tecido bordada', title: 'Bolsa Artesanal', description: 'Bolsa exclusiva com bordados feitos à mão, unindo tradição e estilo.' }
            ];

            function abrirGaleria(item) {
                document.getElementById('modalImage').src = item.src;
                document.getElementById('galleryModalLabel').textContent = item.title;
                document.getElementById('modalDescription').textContent = item.description;
                document.getElementById('galleryOverlay').style.display = 'block';
                document.getElementById('galleryModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function fecharGaleria() {
                document.getElementById('galleryOverlay').style.display = 'none';
                document.getElementById('galleryModal').style.display = 'none';
                document.body.style.overflow = '';
            }

            document.getElementById('galleryCloseBtn').addEventListener('click', fecharGaleria);
            document.getElementById('galleryOverlay').addEventListener('click', fecharGaleria);

            const container = document.getElementById('artesanatos-gallery');
            if (container) {
                galleryItems.forEach((item, i) => {
                    const div = document.createElement('div');
                    div.className = 'bg-white rounded-xl shadow-sm overflow-hidden cursor-pointer card-hover';
                    div.style.animationDelay = (i * 0.1) + 's';
                    div.innerHTML = `
                        <div class="card-img-hover h-44">
                            <img src="${item.src}" alt="${item.alt}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-3 text-center">
                            <h3 class="text-sm font-bold text-brand">${item.title}</h3>
                        </div>
                    `;
                    div.addEventListener('click', () => abrirGaleria(item));
                    container.appendChild(div);
                });
            }
        });
    </script>
@endsection
