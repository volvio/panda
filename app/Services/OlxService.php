<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OlxService
{
    /**
     * Отримує внутрішній offerId OLX за URL оголошення
     *
     * @param  string  $url
     * @return int|null
     */
    public function getOfferIdFromUrl(string $url): ?int
    {
        try {
            // 1. Завантажуємо HTML сторінки оголошення
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'Accept-Language' => 'uk-UA,uk;q=0.9,en-US;q=0.8,en;q=0.7',
            ])->get($url);

            if (!$response->successful()) {
                throw new \Exception("OLX page load failed. Status: {$response->status()}");
            }

            $html = $response->body();

            // 2. Покроково шукаємо Id за різними шаблонами:

            // 1. <span class="css-...">ID: <!-- -->893216480</span>
            if (preg_match('/ID:\s*<!-- -->\s*(\d+)/', $html, $match)) {
                return (int)$match[1];
            }

            //2. "sku":"893216480","offers"
            if (preg_match('/"sku":"(\d+)","offers"/', $html, $match)) {
                return (int)$match[1];
            }

            //3. /purchase/promote/variant/?ad-id=893216480
            if (preg_match('/\/purchase\/promote\/variant\/\?ad-id=(\d+)/', $html, $match)) {
                return (int)$match[1];
            }

            //4. /purchase/refresh/provider/?ad-id=893216480
            if (preg_match('/\/purchase\/refresh\/provider\/\?ad-id=(\d+)/', $html, $match)) {
                return (int)$match[1];
            }

            //5. window.__PRERENDERED_STATE__= "{\"ad\":{\"ad\":{\"id\":893216480
            if (preg_match('/"id":\s*(\d+)/', $html, $match)) {
                return (int)$match[1];
            }

            // Не знайшли нічого
            return null;

        } catch (\Throwable $e) {
            \Log::error('Failed to fetch OLX offer ID: ' . $e->getMessage());
            return null;
        }
    }
}

