<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OlxLink;
use App\Models\OlxPrice;
use App\Services\OlxApiService;
use Illuminate\Support\Facades\Log;

class CheckOlxPrices extends Command
{
    /**
     * Назва команди.
     */
    protected $signature = 'olx:check-prices';

    /**
     * Опис команди.
     */
    protected $description = 'Перевіряє зміни цін на оголошеннях OLX та зберігає нові значення';

    protected OlxApiService $olxApiService;

    public function __construct(OlxApiService $olxApiService)
    {
        parent::__construct();
        $this->olxApiService = $olxApiService;
    }

    /**
     * Основна логіка перевірки цін.
     */
    public function handle()
    {
        $this->info('Починаємо перевірку цін на OLX Україн...');

        // Отримуємо всі посилання, які мають olx_id
        $links = OlxLink::whereNotNull('olx_id')
                        ->where('olx_id', '>', 0)
                        ->get();

        foreach ($links as $link) {
            try {
                $this->info("Проверяем объявление ID: {$link->olx_id}");

                // Отримуємо поточну ціну з OLX.
                $priceInfo = $this->olxApiService->getPriceAndCurrency($link->olx_id);

                if (!$priceInfo) {
                    $this->warn("Не удалось получить цену для ID: {$link->olx_id}");
                    continue;
                }

                // Отримуємо останню ціну
                $lastPrice = OlxPrice::where('olx_link_id', $link->id)
                    ->latest('created_at')
                    ->first();

                $newPrice = $priceInfo['price'];
                $newCurrency = $priceInfo['currency'];

                // Перевіряємо, чи змінилася ціна
                if (!$lastPrice || $lastPrice->price != $newPrice || $lastPrice->currency != $newCurrency) {

                    // Зберігаємо нову ціну
                    OlxPrice::create([
                        'olx_link_id' => $link->id,
                        'price' => $newPrice,
                        'currency' => $newCurrency,
                    ]);

                    // Оновлюємо прапор
                    $link->is_price_update = 1;
                    $link->save();

                    $this->info("Ціна змінилася. Нова: {$newPrice} {$newCurrency}");
                } else {

                    $this->info("Ціна не змінилася: {$lastPrice->price} {$lastPrice->currency}");
                }
            } catch (\Throwable $e) {
                Log::error("Помилка перевірки ціни для {$link->olx_id}: " . $e->getMessage());
                $this->error("Помилка: {$e->getMessage()}");
            }
        }

        $this->info('Перевірку завершено.');
    }
}

