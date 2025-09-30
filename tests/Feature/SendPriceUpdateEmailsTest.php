<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\OlxLink;
use App\Models\OlxSubscriber;
use App\Models\OlxPrice;
use App\Mail\Olx\PriceUpdatedMail;

class SendPriceUpdateEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_email_only_to_confirmed_subscribers()
    {
        Mail::fake();

       // Створюємо URL із оновленням ціни
        $link = OlxLink::factory()->create(['is_price_update' => 1]);

        // Підтверджений передплатник
        $confirmedSubscriber = OlxSubscriber::factory()->create([
            'olx_link_id' => $link->id,
            'confirmed_at' => now(),
        ]);

        // Непідтверджений передплатник
        $unconfirmedSubscriber = OlxSubscriber::factory()->create([
            'olx_link_id' => $link->id,
            'confirmed_at' => null,
        ]);

        // Створюємо нову ціну для посилання
        $price = OlxPrice::factory()->create([
            'olx_link_id' => $link->id,
            'price' => 1200,
            'currency' => 'UAH',
        ]);

        // Запускаємо команду
        $this->artisan('olx:send-price-updates')
            ->expectsOutput("Лист відправлений: {$confirmedSubscriber->email} ({$link->url})")
            ->doesntExpectOutput("Лист відправлений: {$unconfirmedSubscriber->email} ({$link->url})")
            ->expectsOutput('Надсилання повідомлень завершено.')
            ->assertExitCode(0);

     
    }

    #[Test]
    public function test_it_does_nothing_if_no_price_updates()
    {
        Mail::fake();

        // Створюємо посилання без оновлень ціни
        $link = OlxLink::factory()->create(['is_price_update' => 0]);

        $subscriber = OlxSubscriber::factory()->create([
            'olx_link_id' => $link->id,
            'confirmed_at' => now(),
        ]);

        $this->artisan('olx:send-price-updates')
            ->expectsOutput('Немає оновлень ціни для відправки.')
            ->assertExitCode(0);

        Mail::assertNothingQueued();
    }
}
