<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Payment;

class MercadoPagoService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
        MercadoPagoConfig::setIntegratorId('dev_associacao_artesaos');
    }

    public function createPreference(
        float $total,
        array $itens,
        string $externalReference,
        array $backUrls
    ): Preference {
        $successUrl = $backUrls['success'] ?? '';

        $preferenceData = [
            'items' => array_map(fn ($item) => [
                'id' => (string) $item['id'],
                'title' => $item['nome'],
                'quantity' => (int) $item['quantidade'],
                'unit_price' => (float) $item['preco'],
                'currency_id' => 'BRL',
            ], $itens),
            'external_reference' => $externalReference,
            'back_urls' => [
                'success' => $successUrl,
                'pending' => $backUrls['pending'] ?? '',
                'failure' => $backUrls['failure'] ?? '',
            ],
            'notification_url' => $backUrls['notification'] ?? null,
        ];

        if (str_starts_with($successUrl, 'https://')) {
            $preferenceData['auto_return'] = 'approved';
        }

        $client = new PreferenceClient();
        return $client->create($preferenceData);
    }

    public function getPaymentInfo(int $paymentId): Payment
    {
        $client = new PaymentClient();
        return $client->get($paymentId);
    }
}
