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

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <i class="bi bi-whatsapp text-success" style="font-size: 4rem;"></i>
            </div>
            <h1 class="fw-bold mb-3" style="color: #5C3A2C;">Falta Pouco!</h1>
            <p class="text-muted mb-2">Seu pedido <strong>#{{ $venda->id_venda }}</strong> foi gerado e salvo com sucesso no sistema.</p>
            <p class="text-muted mb-4">Total: <strong class="fs-4" style="color: #c85a3a;">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</strong></p>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body text-start p-4">
                    <h5 class="fw-bold mb-3" style="color: #5C3A2C;">Resumo do Pedido</h5>
                    <p class="mb-1"><strong>Cliente:</strong> {{ $venda->cliente->nome }}</p>
                    <p class="mb-1"><strong>E-mail:</strong> {{ $venda->cliente->email }}</p>
                    <p class="mb-1"><strong>Data:</strong> {{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}</p>
                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-warning text-dark">Aguardando Pagamento/Frete</span></p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3" style="color: #5C3A2C;">Itens</h5>
                    @foreach($venda->itens as $item)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>{{ $item->produto->nome }} <small class="text-muted">x{{ $item->quantidade }}</small></span>
                        <span class="fw-bold">R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</span>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-between pt-3">
                        <span class="fw-bold fs-5" style="color: #5C3A2C;">Total</span>
                        <span class="fw-bold fs-5" style="color: #c85a3a;">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-lg w-100 px-5 text-white fw-bold mb-3 d-flex align-items-center justify-content-center gap-2 shadow-sm" style="background-color: #25D366; border-radius: 50rem;">
                <i class="bi bi-whatsapp fs-4"></i> Concluir Pagamento no WhatsApp
            </a>

            <a href="{{ route('produtos') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-2"></i> Voltar aos Produtos
            </a>
        </div>
    </div>
</div>
@endsection
