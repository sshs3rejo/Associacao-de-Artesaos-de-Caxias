@extends('layouts.main')
@section('titulo', 'Pagamento Confirmado')

@section('content')
@php
    $whatsappNumber = preg_replace('/[^0-9]/', '', config('association.whatsapp', '5599981597539'));
    $texto = "Olá! Fiz o pedido #" . $venda->id_venda . " no site.\n\n";
    $texto .= "*Resumo do Pedido:*\n";
    foreach($venda->itens as $item) {
        $texto .= "- " . $item->quantidade . "x " . $item->produto->nome . " (R$ " . number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') . ")\n";
    }
    $texto .= "\n*Total:* R$ " . number_format($venda->valor_total, 2, ',', '.') . "\n\n";
    $texto .= "Gostaria de combinar o pagamento via Pix e a forma de entrega!";

    $whatsappUrl = "https://api.whatsapp.com/send?phone=" . $whatsappNumber . "&text=" . urlencode($texto);
@endphp

<div class="max-w-7xl mx-auto px-4 py-5">
    <div class="flex justify-center">
        <div class="w-full md:w-1/2 text-center">
            <div class="mb-4">
                <i class="fab fa-whatsapp text-green-600" style="font-size: 4rem;"></i>
            </div>
            <h1 class="font-bold mb-3" style="color: #5C3A2C;">Falta Pouco!</h1>
            <p class="text-gray-500 mb-2">Seu pedido <strong>#{{ $venda->id_venda }}</strong> foi gerado e salvo com sucesso no sistema.</p>
            <p class="text-gray-500 mb-4">Total: <strong class="text-xl text-price">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</strong></p>

            <div class="bg-white rounded-xl shadow-sm mb-4">
                <div class="p-4 text-start">
                    <h5 class="font-bold mb-3" style="color: #5C3A2C;">Resumo do Pedido</h5>
                    <p class="mb-1"><strong>Cliente:</strong> {{ $venda->cliente->nome }}</p>
                    <p class="mb-1"><strong>E-mail:</strong> {{ $venda->cliente->email }}</p>
                    <p class="mb-1"><strong>Data:</strong> {{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}</p>
                    <p class="mb-0"><strong>Status:</strong> <x-badge type="success">Pago</x-badge></p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm mb-4">
                <div class="p-4">
                    <h5 class="font-bold mb-3" style="color: #5C3A2C;">Itens</h5>
                    @foreach($venda->itens as $item)
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span>{{ $item->produto->nome }} <small class="text-gray-500">x{{ $item->quantidade }}</small></span>
                        <span class="font-bold">R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between pt-3">
                        <span class="font-bold text-xl" style="color: #5C3A2C;">Total</span>
                        <span class="font-bold text-xl text-price">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <a href="{{ $whatsappUrl }}" target="_blank" class="inline-block px-4 py-2 rounded-lg font-semibold w-full px-5 text-white font-bold mb-3 flex items-center justify-center gap-2 shadow-sm" style="background-color: #25D366; border-radius: 50rem;">
                <i class="fab fa-whatsapp text-xl"></i> Concluir Pagamento no WhatsApp
            </a>

            <a href="{{ route('produtos') }}" class="inline-block px-4 py-2 rounded-lg font-semibold border-2 border-gray-400 text-gray-600 hover:bg-gray-100 transition rounded-full px-4 font-bold">
                <i class="fas fa-arrow-left me-2"></i> Voltar aos Produtos
            </a>
        </div>
    </div>
</div>
@endsection
