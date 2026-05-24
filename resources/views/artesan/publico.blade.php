@extends('layouts.main')
@section('titulo', $user->name . ' - Artesão')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-5">
    <div class="flex justify-center">
        <div class="w-full lg:w-2/3 text-center mb-5">
            <div class="mb-4">
                @if($perfil->profile_photo)
                    <img src="{{ asset('storage/' . $perfil->profile_photo) }}" alt="{{ $user->name }}"
                         class="rounded-full shadow-sm" style="width: 150px; height: 150px; object-fit: cover;" loading="lazy">
                @else
                    <div class="rounded-full inline-flex items-center justify-center shadow-sm"
                         style="width: 150px; height: 150px; background-color: #F9F7D3;">
                        <i class="fas fa-user text-4xl text-brand"></i>
                    </div>
                @endif
            </div>
            <h1 class="font-bold mb-2 text-brand">{{ $user->name }}</h1>
            @if($perfil->specialty)
                <p class="text-lg text-gray-500 mb-3"><i class="fas fa-star me-2"></i>{{ $perfil->specialty }}</p>
            @endif
            @if($perfil->bio)
                <p class="text-xl text-gray-600" style="max-width: 600px; margin: 0 auto;">{{ $perfil->bio }}</p>
            @endif
            <div class="flex justify-center gap-3 mt-4">
                @if($perfil->instagram)
                    <a href="https://instagram.com/{{ ltrim($perfil->instagram, '@') }}" target="_blank" class="inline-block px-4 py-2 rounded-lg font-semibold border-2 border-gray-800 text-gray-800 hover:bg-gray-800 hover:text-white transition rounded-full">
                        <i class="fab fa-instagram me-1"></i> Instagram
                    </a>
                @endif
                @if($perfil->facebook)
                    <a href="{{ $perfil->facebook }}" target="_blank" class="inline-block px-4 py-2 rounded-lg font-semibold border-2 border-gray-800 text-gray-800 hover:bg-gray-800 hover:text-white transition rounded-full">
                        <i class="fab fa-facebook me-1"></i> Facebook
                    </a>
                @endif
                @if($perfil->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $perfil->whatsapp) }}" target="_blank" class="inline-block px-4 py-2 rounded-lg font-semibold bg-green-500 text-white hover:bg-green-600 transition rounded-full">
                        <i class="fab fa-whatsapp me-1"></i> WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($produtos->isNotEmpty())
        <h2 class="font-bold text-center mb-4 text-brand">Produtos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($produtos as $produto)
                <div>
                    <div class="bg-white rounded-xl shadow-sm h-full overflow-hidden">
                        <div class="overflow-hidden" style="height: 180px;">
                            <img src="{{ $produto->imagem ? asset('storage/' . $produto->imagem) : config('association.placeholder') }}"
                                 class="w-full h-full" alt="{{ $produto->nome }}" style="object-fit: cover;" loading="lazy">
                        </div>
                        <div class="p-3">
                            <h5 class="font-bold mb-1 text-brand">{{ $produto->nome }}</h5>
                            <span class="font-bold text-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
