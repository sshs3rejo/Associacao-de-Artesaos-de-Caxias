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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 justify-content-center">
            <!-- Contatos rápidos -->
            <div class="col-md-4">
                <div class="contact-card text-center h-100">
                    <h2 class="h4 fw-bold mb-4" style="color: #7a2f1f;">Contato Imediato</h2>
                    <div class="d-flex flex-column gap-3">
                        <a class="btn btn-outline-dark btn-lg animacao d-flex align-items-center justify-content-center gap-2" href="https://www.instagram.com/artesaosdecaxias_ma" target="_blank">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                        <a class="btn btn-outline-dark btn-lg animacao d-flex align-items-center justify-content-center gap-2" href="mailto:artesaosdecaxiasma@gmail.com">
                            <i class="fas fa-envelope"></i> E-mail
                        </a>
                        <a class="btn btn-success btn-lg animacao d-flex align-items-center justify-content-center gap-2" href="https://wa.me/5599981597539" target="_blank">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulário -->
            <div class="col-md-8">
                <div class="contact-card">
                    <h2 class="h4 fw-bold mb-4" style="color: #7a2f1f;">Formulário de Contato</h2>
                    <form action="{{ route('contato.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-bold">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control form-control-lg @error('nome') is-invalid @enderror" placeholder="Digite seu nome completo" value="{{ old('nome') }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Digite seu e-mail" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label fw-bold">Mensagem</label>
                            <textarea id="mensagem" name="mensagem" class="form-control form-control-lg @error('mensagem') is-invalid @enderror" rows="4" placeholder="Escreva sua mensagem..." required>{{ old('mensagem') }}</textarea>
                            @error('mensagem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-lg w-100 fw-bold" style="background-color: #7a2f1f; color: #F9F7D3;">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
