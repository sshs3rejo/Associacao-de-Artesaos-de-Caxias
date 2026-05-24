@extends('layouts.main')

@section('titulo', 'Contato')

@section('style')
    <style>
        .contact-hero {
            background: linear-gradient(rgba(122, 46, 29, 0.7), rgba(122, 46, 29, 0.7)), url('{{ asset('imagens/banquin.webp') }}') no-repeat center/cover;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="contact-hero text-center mb-12 hero-section flex items-center min-h-[35vh] md:min-h-[50vh] py-10 md:py-20">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">Venha nos fazer uma visita</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto" style="color: rgba(255,255,255,0.9);">Entre em contato ou agende uma visita conosco para conhecer nossa associação.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 mb-12">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm text-brand hover:text-brand-dark font-medium transition-colors mb-3">
            <i class="fas fa-arrow-left text-xs"></i> Voltar
        </a>
        <x-alert type="success" :message="session('success')" />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Contatos rápidos -->
            <div class="md:col-span-1">
                <div class="contact-card text-center h-full">
                    <h2 class="text-xl font-bold mb-6" style="color: #7a2f1f;">Contato Imediato</h2>
                    <div class="flex flex-col gap-3">
                        <a class="border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition px-6 py-3 text-lg animacao flex items-center justify-center gap-2 rounded-lg" href="{{ config('association.instagram') }}" target="_blank">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                        <a class="border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition px-6 py-3 text-lg animacao flex items-center justify-center gap-2 rounded-lg" href="mailto:{{ config('association.email') }}">
                            <i class="fas fa-envelope"></i> E-mail
                        </a>
                        <a class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 text-lg animacao flex items-center justify-center gap-2 rounded-lg transition" href="https://wa.me/{{ config('association.whatsapp') }}" target="_blank">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mapa + Formulário -->
            <div class="md:col-span-2">
                <!-- Mapa -->
                <div class="contact-card mb-6">
                    <h2 class="text-xl font-bold mb-4" style="color: #7a2f1f;">Nossa Localização</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        <i class="bi bi-geo-alt-fill mr-1"></i> {{ config('association.address') }}
                    </p>
                    <div class="rounded-lg overflow-hidden border" style="height: 300px;">
                        <iframe
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q={{ config('association.latitude') }},{{ config('association.longitude') }}&hl=pt-BR&z=16&output=embed">
                        </iframe>
                    </div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ config('association.latitude') }},{{ config('association.longitude') }}"
                       target="_blank"
                       class="border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition px-4 py-2 text-sm rounded-full mt-4 w-full inline-block text-center animacao">
                        <i class="fas fa-directions mr-1"></i> Abrir no Google Maps
                    </a>
                </div>

                <!-- Formulário -->
                <div class="contact-card">
                    <h2 class="text-xl font-bold mb-6" style="color: #7a2f1f;">Formulário de Contato</h2>
                    <form action="{{ route('contato.store') }}" method="post">
                        @csrf
                        <div class="mb-4">
                            <label for="nome" class="block font-bold mb-1">Nome</label>
                            <input type="text" id="nome" name="nome" class="w-full border border-gray-300 rounded-lg px-4 py-3 @error('nome') border-red-500 @enderror" placeholder="Digite seu nome completo" value="{{ old('nome') }}" required>
                            @error('nome')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block font-bold mb-1">E-mail</label>
                            <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-3 @error('email') border-red-500 @enderror" placeholder="Digite seu e-mail" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="mensagem" class="block font-bold mb-1">Mensagem</label>
                            <textarea id="mensagem" name="mensagem" class="w-full border border-gray-300 rounded-lg px-4 py-3 @error('mensagem') border-red-500 @enderror" rows="4" placeholder="Escreva sua mensagem..." required>{{ old('mensagem') }}</textarea>
                            @error('mensagem')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="w-full text-lg font-bold px-6 py-3 rounded-lg inline-block text-center transition" style="background-color: #7a2f1f; color: #F9F7D3;">Enviar Mensagem</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
