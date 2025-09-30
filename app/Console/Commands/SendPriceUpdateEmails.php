<?php

namespace App\Console\Commands;

use App\Mail\OlxPriceUpdatedMail;
use App\Models\OlxLink;
use App\Models\OlxPrice;
use App\Models\OlxSubscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPriceUpdateEmails extends Command
{
    /**
     * Назва команди Artisan
     *
     * @var string
     */
    protected $signature = 'olx:send-price-updates';

    /**
     * Опис команди
     *
     * @var string
     */
    protected $description = 'Відправляє повідомлення користувачам про зміну ціни оголошення OLX.';

    /**
     * Виконання команди
     */
    public function handle()
    {
        $this->info('Пошук оголошень зі зміненою ціною...');

        //Отримуємо всі оголошення, у яких ціна змінилася
        $links = OlxLink::where('is_price_update', 1)->get();

        if ($links->isEmpty()) {
            $this->info('Немає оновлень ціни для відправки.');
            return Command::SUCCESS;
        }

        foreach ($links as $link) {
            //Отримуємо останню ціну з таблиці olx_prices
            $latestPrice = OlxPrice::where('olx_link_id', $link->id)
                ->latest('created_at')
                ->first();

            if (!$latestPrice) {
                $this->warn("Пропущено оголошення ID={$link->id} - немає даних про ціну.");
                continue;
            }

            //Отримуємо всіх підтверджених передплатників
            $subscribers = OlxSubscriber::where('olx_link_id', $link->id)
                ->whereNotNull('confirmed_at')
                ->get();

            if ($subscribers->isEmpty()) {
                $this->info("Немає підтверджених передплатників для{$link->url}");
            }

            foreach ($subscribers as $subscriber) {
                //Надсилаємо лист
                Mail::to($subscriber->email)->send(
                    new OlxPriceUpdatedMail($link, $latestPrice)
                );

                $this->info("Лист відправлений: {$subscriber->email} ({$link->url})");
            }

            //Скидаємо прапор оновлення
            $link->is_price_update = 0;
            $link->save();
        }

        $this->info('Надсилання повідомлень завершено.');
        return Command::SUCCESS;
    }
}
