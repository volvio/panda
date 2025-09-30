<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\OlxLink;
use App\Models\OlxPrice;

class CheckPricesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_price_if_changed()
    {
        $link = OlxLink::factory()->create(['olx_id' => '12345']);
        OlxPrice::factory()->create([
            'olx_link_id' => $link->id,
            'price' => 1000,
            'currency' => 'UAH'
        ]);

        $this->mock(\App\Services\OlxApiService::class, function ($mock) {
            $mock->shouldReceive('getPriceAndCurrency')->andReturn([
                'price' => 1200,
                'currency' => 'UAH'
            ]);
        });

        $this->artisan('olx:check-prices')
             ->expectsOutput('Ціна змінилася. Нова: 1200 UAH')
             ->assertExitCode(0);

        $this->assertDatabaseHas('olx_prices', ['price' => 1200]);
        $this->assertDatabaseHas('olx_links', ['is_price_update' => 1]);
    }
    
    public function test_it_updates_price_no_changed()
    {
        $link = OlxLink::factory()->create(['olx_id' => '12345']);
        OlxPrice::factory()->create([
            'olx_link_id' => $link->id,
            'price' => 1000,
            'currency' => 'UAH'
        ]);

        $this->mock(\App\Services\OlxApiService::class, function ($mock) {
            $mock->shouldReceive('getPriceAndCurrency')->andReturn([
                'price' => 1000,
                'currency' => 'UAH'
            ]);
        });

        $this->artisan('olx:check-prices')
             ->expectsOutput('Ціна не змінилася: 1000.00 UAH')
             ->assertExitCode(0);

    }
}
