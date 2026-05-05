@extends('layouts.main')

@section('titulo', 'Sobre Nós')

@section('style')
<style>
    .about-hero {
        background: linear-gradient(rgba(122, 46, 29, 0.75), rgba(122, 46, 29, 0.75)), url('{{ asset('imagens/fundo.png') }}') no-repeat center/cover;
        padding: 80px 0;
        color: white;
    }
    .about-card {
        background: white;
        border-radius: 15px;
        padding: 40px;
        margin-bottom: 30px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .about-card h2 {
        color: #7a2f1f;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .about-card p {
        color: #555;
        font-size: 1.1rem;
        line-height: 1.8;
    }
</style>
@endsection

@section('content')
<div class="about-hero text-center mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold">Sobre a Associação</h1>
        <p class="lead">Conheça nossa história, nossa missão e as pessoas que tornam o artesanato de Caxias único.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="about-card shadow-sm">
                <h2><i class="bi bi-people-fill me-2"></i> Nossa Missão</h2>
                <p>
                    A Associação dos Artesãos de Caxias tem como missão fortalecer, divulgar e valorizar o trabalho dos artesãos locais. Conectamos produtores, comunidade e novos públicos, promovendo o artesanato como expressão cultural e fonte de renda sustentável.
                </p>
            </div>

            <div class="about-card shadow-sm">
                <h2><i class="bi bi-journal-text me-2"></i> Nossa História</h2>
                <p>
                    Nascemos da união de artesãos apaixonados pela cultura maranhense e estudantes de tecnologia da UniFacema. Nosso objetivo inicial era criar uma ponte digital para organizar e divulgar as atividades artesanais, facilitando a gestão e a comunicação entre os membros.
                </p>
            </div>

            <div class="about-card shadow-sm">
                <h2><i class="bi bi-person-hearts me-2"></i> Nossa Equipe</h2>
                <p>
                    Nossa equipe é formada por mestres artesãos, voluntários engajados e estudantes dedicados. Cada integrante contribui com um talento único — seja na técnica ancestral do barro, na delicadeza do bordado ou no desenvolvimento de soluções que impulsionam a economia criativa de Caxias.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
