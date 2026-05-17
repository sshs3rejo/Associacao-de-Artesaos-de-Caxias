@extends('layouts.main')

@section('titulo', config('association.name_short') . ' - Home')

@section('content')
    <!-- HERO SECTION -->
    <section id="home-section" class="py-5" style="background: linear-gradient(rgba(249, 247, 211, 0.8), rgba(249, 247, 211, 0.8)), url('{{ asset('imagens/artesanato_alunos/back-logo.png') }}') no-repeat center/contain; min-height: 80vh; display: flex; align-items: center;">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4" style="color: #7a2f1f;">Aqui, o simples ganha forma, o barro respira, a palha canta e as mãos viram poesia.</h1>
            <p class="lead mb-5 text-muted">A Associação dos Artesãos de Caxias é um espaço onde a arte popular se encontra com o empreendedorismo criativo. Nosso propósito é conectar quem cria com quem valoriza o feito à mão.</p>
            
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('sobrenos') }}" class="btn btn-lg px-4 fw-bold" style="background-color: #7a2f1f; color: #F9F7D3;">Conhecer a Associação</a>
                <a href="{{ route('produtos') }}" class="btn btn-lg btn-outline-dark px-4 fw-bold" style="border-color: #7a2f1f; color: #7a2f1f;">Ver Produtos</a>
            </div>
        </div>
    </section>



    <!-- EVENTOS -->
    <section id="eventos-section" class="py-5">
        <div class="container">
            <h2 class="display-5 fw-bold text-center mb-5" style="color: #7a2f1f;">Eventos e Atividades</h2>
            
            @if($eventos->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted fs-5">Nenhum evento futuro cadastrado no momento. Volte em breve!</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($eventos as $evento)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0">
                                @if($evento->imagem)
                                    <img src="{{ asset('storage/' . $evento->imagem) }}" class="card-img-top" alt="{{ $evento->nome }}" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h3 class="h4 card-title fw-bold" style="color: #7a2f1f;">{{ $evento->nome }}</h3>
                                    <p class="card-text text-muted mb-4">
                                        <i class="bi bi-calendar-event me-2"></i>
                                        {{ $evento->data_inicio ? $evento->data_inicio->format('d/m/Y H:i') : 'Data a definir' }}
                                    </p>
                                    <div class="mt-auto">
                                        <a href="{{ route('eventos.show', $evento->id_evento) }}" class="btn w-100 fw-bold" style="background-color: #7a2f1f; color: #F9F7D3;">Ver Detalhes</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <div class="text-center mt-5">
                <a href="{{ route('evento') }}" class="btn btn-lg btn-outline-dark px-5 fw-bold" style="border-color: #7a2f1f; color: #7a2f1f;">Ver Todos os Eventos</a>
            </div>
        </div>
    </section>

    <!-- ARTESANATOS -->
    <section id="artesanatos-section" class="py-5" style="background-color: #f0eecf;">
        <div class="container">
            <h2 class="display-5 fw-bold text-center mb-5" style="color: #7a2f1f;">Nossos Artesanatos</h2>
            <p class="text-center fs-5 mb-5">Nossa loja colaborativa é uma vitrine do talento de Caxias.</p>

            <div class="row row-cols-2 row-cols-md-4 g-4" id="artesanatos-gallery">
                <!-- Galeria será populada via JavaScript -->
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('produtos') }}" class="btn btn-lg px-5 fw-bold" style="background-color: #7a2f1f; color: #F9F7D3;">Ver Todos os Produtos</a>
            </div>
        </div>
    </section>

    <!-- MODAL DA GALERIA (Custom, sem Bootstrap Modal) -->
    <div id="galleryOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9998;"></div>
    <div id="galleryModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); z-index:9999; width:90%; max-width:800px; max-height:90vh; background:#F9F7D3; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow-y:auto;">
        <div style="display:flex; align-items:center; justify-content:space-between; padding:20px 24px 0;">
            <h5 id="galleryModalLabel" style="margin:0; font-weight:700; color:#7a2f1f;">Título</h5>
            <button id="galleryCloseBtn" style="background:none; border:none; font-size:28px; cursor:pointer; color:#7a2f1f; line-height:1;">&times;</button>
        </div>
        <div style="text-align:center; padding:20px 24px 24px;">
            <img id="modalImage" src="" alt="" style="max-width:100%; max-height:500px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); margin-bottom:16px;">
            <p id="modalDescription" style="font-size:1.1rem; color:#6c757d; margin:0;">Descrição</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const galleryItems = [
                { 
                    src: '{{ asset('imagens/artesanato_alunos/artesanato1.jpg') }}', 
                    alt: 'Cesta de palha artesanal', 
                    title: 'Cesta de Palha', 
                    description: 'Cesta tecida à mão com fibras naturais, ideal para decoração ou uso diário.' 
                },
                { 
                    src: '{{ asset('imagens/artesanato_alunos/artesanato2.jpg') }}', 
                    alt: 'Escultura de madeira rústica', 
                    title: 'Escultura em Madeira', 
                    description: 'Obra de arte entalhada em madeira rústica, representando a fauna local.' 
                },
                { 
                    src: '{{ asset('imagens/artesanato_alunos/artesanato3.jpg') }}', 
                    alt: 'Cerâmica pintada à mão', 
                    title: 'Vaso de Cerâmica', 
                    description: 'Vaso de cerâmica com pintura manual, um toque de arte para seu lar.' 
                },
                { 
                    src: '{{ asset('imagens/artesanato_alunos/artesanato4.jpg') }}', 
                    alt: 'Bolsa de tecido bordada', 
                    title: 'Bolsa Artesanal', 
                    description: 'Bolsa exclusiva com bordados feitos à mão, unindo tradição e estilo.' 
                }
            ];

            function abrirGaleria(item) {
                document.getElementById('modalImage').src = item.src;
                document.getElementById('galleryModalLabel').textContent = item.title;
                document.getElementById('modalDescription').textContent = item.description;
                document.getElementById('galleryOverlay').style.display = 'block';
                document.getElementById('galleryModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function fecharGaleria() {
                document.getElementById('galleryOverlay').style.display = 'none';
                document.getElementById('galleryModal').style.display = 'none';
                document.body.style.overflow = '';
            }

            document.getElementById('galleryCloseBtn').addEventListener('click', fecharGaleria);
            document.getElementById('galleryOverlay').addEventListener('click', fecharGaleria);

            const galleryContainer = document.getElementById('artesanatos-gallery');

            if (galleryContainer) {
                galleryItems.forEach((item) => {
                    const col = document.createElement('div');
                    col.className = 'col';
                    col.innerHTML = `
                        <div class="card h-100 shadow-sm border-0 text-center p-3 cursor-pointer" style="cursor: pointer; transition: transform 0.2s;">
                            <img src="${item.src}" alt="${item.alt}" class="card-img-top rounded mb-3" style="height: 180px; object-fit: cover;">
                            <h3 class="h6 mb-0 fw-bold" style="color: #7a2f1f;">${item.title}</h3>
                        </div>
                    `;
                    
                    col.querySelector('.card').addEventListener('click', () => abrirGaleria(item));
                    
                    // Efeito hover simples
                    col.querySelector('.card').addEventListener('mouseenter', function() { this.style.transform = 'translateY(-5px)'; });
                    col.querySelector('.card').addEventListener('mouseleave', function() { this.style.transform = 'translateY(0)'; });
                    
                    galleryContainer.appendChild(col);
                });
            }
        });
    </script>
@endsection