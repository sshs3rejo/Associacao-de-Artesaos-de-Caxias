@extends('layouts.main')

@section('titulo', 'Contato')

@section('style')
    <style>
        .contact-hero {
            background: linear-gradient(rgba(122, 46, 29, 0.7), rgba(122, 46, 29, 0.7)), url('{{ asset('imagens/banquin.png') }}') no-repeat center/cover;
            padding: 80px 0;
            color: white;
        }
        .contact-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .animacao {
            transition: transform 0.3s ease;
        }
        .animacao:hover {
            transform: scale(1.1);
        }
    </style>
@endsection

@section('content')
    <div class="contact-hero text-center mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Venha nos fazer uma visita</h1>
            <p class="lead">Entre em contato ou agende uma visita conosco para conhecer nossa associação.</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row g-4 justify-content-center">
            <!-- Contatos rápidos -->
            <div class="col-md-4">
                <div class="contact-card text-center h-100">
                    <h2 class="h4 fw-bold mb-4" style="color: #7a2f1f;">Contato Imediato</h2>
                    <div class="d-flex justify-content-center gap-3">
                        <a class="btn btn-outline-dark btn-lg animacao" href="https://www.instagram.com/artesaosdecaxias_ma" target="_blank" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="btn btn-outline-dark btn-lg animacao" href="tel:+5599999999999" title="Telefone">
                            <i class="fas fa-phone"></i>
                        </a>
                        <a class="btn btn-outline-dark btn-lg animacao" href="mailto:artesaosdecaxiasma@gmail.com" title="E-mail">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulário -->
            <div class="col-md-8">
                <div class="contact-card">
                    <h2 class="h4 fw-bold mb-4" style="color: #7a2f1f;">Formulário de Contato</h2>
                    <form action="#" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-bold">Nome</label>
                            <input type="text" id="nome" class="form-control form-control-lg" placeholder="Digite seu nome completo">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">E-mail</label>
                            <input type="email" id="email" class="form-control form-control-lg" placeholder="Digite seu e-mail">
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label fw-bold">Mensagem</label>
                            <textarea id="mensagem" class="form-control form-control-lg" rows="4" placeholder="Escreva sua mensagem..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-lg w-100 fw-bold" style="background-color: #7a2f1f; color: #F9F7D3;">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
