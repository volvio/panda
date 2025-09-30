<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\OlxLink;
use App\Models\OlxSubscriber;

class OlxSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_subscription_and_sends_email()
    {
        $this->mock(\App\Services\OlxService::class, function ($mock) {
            $mock->shouldReceive('getOfferIdFromUrl')->andReturn('12345');
        });

        $this->mock(\App\Services\OlxApiService::class, function ($mock) {
            $mock->shouldReceive('getPriceAndCurrency')->andReturn([
                'price' => 1000,
                'currency' => 'UAH'
            ]);
        });

        $response = $this->post('/olx/subscribe', [
            'url' => 'https://www.olx.ua/d/uk/obyavlenie/test-ID12345.html',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('olx_links', ['olx_id' => '12345']);
        $this->assertDatabaseHas('olx_subscribers', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('olx_prices', ['price' => 1000, 'currency' => 'UAH']);
    }
}
