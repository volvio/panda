<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OlxApiService
{
    /**
     * Получить цену и валюту объявления по его ID из OLX API
     *
     * @param  int|string  $olxId
     * @return array|null  ['price' => 44999, 'currency' => 'UAH'] или null при ошибке
     */
    public function getPriceAndCurrency($olxId): ?array
    {
        $url = "https://www.olx.ua/api/v1/offers/{$olxId}";

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'User-Agent' => 'Mozilla/5.0 (compatible; PriceTracker/1.0)'
            ])->get($url);

            if (!$response->ok()) {
                Log::warning("OLX API error: {$response->status()} for ID {$olxId}");
                return null;
            }

            $data = $response->json('data');

            if (!$data || !isset($data['params'])) {
                Log::warning("OLX API: params not found for ID {$olxId}");
                return null;
            }

            // Найти параметр price
            foreach ($data['params'] as $param) {
                if (($param['key'] ?? '') === 'price') {
                    $value = $param['value'] ?? [];
                    return [
                        'price' => $value['value'] ?? null,
                        'currency' => $value['currency'] ?? null
                    ];
                }
            }

            Log::warning("OLX API: price param not found for ID {$olxId}");
            return null;

        } catch (\Throwable $e) {
            Log::error("OLX API request failed: " . $e->getMessage());
            return null;
        }
    }
}
