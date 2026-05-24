@extends('layouts.main')
@section('titulo', 'Pagamento Pendente')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-5">
    <div class="flex justify-center">
        <div class="w-full md:w-1/2 text-center">
            <div class="mb-4">
                <i class="fas fa-clock text-yellow-500" style="font-size: 4rem;"></i>
            </div>
            <h1 class="font-bold mb-3" style="color: #5C3A2C;">Pagamento não finalizado</h1>
            <p class="text-gray-500 mb-4">O pagamento do pedido <strong>#{{ $venda->id_venda }}</strong> não foi concluído ou está pendente.</p>

            <div class="bg-white rounded-xl shadow-sm mb-4">
                <div class="p-4 text-start">
                    <h5 class="font-bold mb-3" style="color: #5C3A2C;">Detalhes</h5>
                    <p class="mb-1"><strong>Cliente:</strong> {{ $venda->cliente->nome }}</p>
                    <p class="mb-1"><strong>Valor:</strong> R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</p>
                    <p class="mb-0"><strong>Status:</strong> <x-badge type="pending">Pendente</x-badge></p>
                </div>
            </div>

            <div class="flex gap-3 justify-center">
                <a href="{{ route('produtos') }}" class="inline-block px-4 py-2 rounded-lg font-semibold text-lg px-4 font-bold border-2 border-brand text-brand hover:bg-brand hover:text-white transition rounded-full">
                    <i class="fas fa-arrow-left me-2"></i> Voltar às Compras
                </a>
                <a href="{{ route('home') }}" class="inline-block px-4 py-2 rounded-lg font-semibold text-lg px-4 text-white font-bold rounded-full bg-brand hover:bg-brand-dark transition">
                    Página Inicial
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
