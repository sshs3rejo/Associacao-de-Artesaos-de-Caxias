@extends('layouts.main')

@section('titulo', 'Sobre Nós')

@section('style')
<style>
    .about-hero {
        background: linear-gradient(rgba(122, 46, 29, 0.75), rgba(122, 46, 29, 0.75)), url('{{ asset('imagens/fundo.webp') }}') no-repeat center/cover;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="about-hero text-center mb-5 hero-section flex items-center min-h-[35vh] md:min-h-[50vh] py-10 md:py-20">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">Sobre a Associação</h1>
        <p class="text-lg md:text-xl max-w-2xl mx-auto" style="color: rgba(255,255,255,0.9);">Conheça nossa história, nossa missão e as pessoas que tornam o artesanato de Caxias único.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 mb-5">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
        <i class="fas fa-arrow-left text-xs"></i> Voltar
    </a>
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2><i class="fas fa-users me-2"></i> Nossa Missão</h2>
                <p>
                    A Associação dos Artesãos de Caxias tem como missão fortalecer, divulgar e valorizar o trabalho dos artesãos locais. Conectamos produtores, comunidade e novos públicos, promovendo o artesanato como expressão cultural e fonte de renda sustentável.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2><i class="fas fa-book me-2"></i> Nossa História</h2>
                <p>
                    Nascemos da união de artesãos apaixonados pela cultura maranhense e estudantes de tecnologia da UniFacema. Nosso objetivo inicial era criar uma ponte digital para organizar e divulgar as atividades artesanais, facilitando a gestão e a comunicação entre os membros.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h2><i class="fas fa-hand-holding-heart me-2"></i> Nossa Equipe</h2>
                <p>
                    Nossa equipe é formada por mestres artesãos, voluntários engajados e estudantes dedicados. Cada integrante contribui com um talento único — seja na técnica ancestral do barro, na delicadeza do bordado ou no desenvolvimento de soluções que impulsionam a economia criativa de Caxias.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
