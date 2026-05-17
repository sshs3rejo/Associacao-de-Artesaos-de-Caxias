<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use App\Models\Vendas;
use App\Models\Estoques;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    public function __construct(
        protected MercadoPagoService $mercadoPago
    ) {}

    public function handle(Request $request)
    {
        $secret = config('mercadopago.webhook_secret');
        if ($secret && $request->header('x-webhook-secret') !== $secret) {
            Log::warning('MP webhook: invalid secret');
            return response()->json(['error' => 'invalid secret'], 401);
        }

        $topic = $request->input('topic');
        $id = $request->input('id');

        if ($topic !== 'payment' || !$id) {
            return response()->json(['error' => 'ignored'], 200);
        }

        try {
            $payment = $this->mercadoPago->getPaymentInfo((int) $id);
            if (!$payment) {
                Log::warning("MP webhook: payment {$id} not found");
                return response()->json(['error' => 'payment not found'], 404);
            }

            $vendaId = $payment->external_reference ?? null;
            if (!$vendaId) {
                return response()->json(['error' => 'no external reference'], 200);
            }

            $venda = Vendas::find($vendaId);
            if (!$venda) {
                Log::warning("MP webhook: venda {$vendaId} not found for payment {$id}");
                return response()->json(['error' => 'venda not found'], 404);
            }

            $venda->update([
                'mp_payment_id' => $id,
                'mp_status' => $this->mapMpStatus($payment->status),
            ]);

            // O estoque já foi pré-reservado/decrementado de forma segura no CheckoutController
            // para evitar concorrência. Aqui apenas atualizamos o status do pagamento.

            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            Log::error("MP webhook error: {$e->getMessage()}");
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function mapMpStatus(string $mpStatus): string
    {
        return match ($mpStatus) {
            'approved' => 'approved',
            'rejected' => 'rejected',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
            'charged_back' => 'refunded',
            default => 'pending',
        };
    }
}
