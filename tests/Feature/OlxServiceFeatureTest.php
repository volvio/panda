<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\OlxService;

class OlxServiceFeatureTest extends TestCase
{
    public function test_it_extracts_offer_id_from_html_response(): void
    {
        $service = new OlxService();

        $url = 'https://www.olx.ua/d/uk/obyavlenie/xiaomi-15-ultra-16-512-black-vropeyska-versya-IDYrQpq.html';

        // Мокаем HTTP-запрос
        Http::fake([
            $url => Http::response(
                '<html>
                    <body>
                        <span class="css-ooacec">ID: <!-- -->893216480</span>
                    </body>
                </html>', 
                200
            )
        ]);

        $olxId = $service->getOfferIdFromUrl($url);

        // Перевіряємо, що ми отримали правильний ID
        $this->assertEquals('893216480', $olxId);
    }

    public function test_it_returns_null_for_invalid_html(): void
    {
        $service = new OlxService();
        $url = 'https://www.olx.ua/d/uk/obyavlenie/invalid.html';

        Http::fake([
            $url => Http::response('<html><body>Нет ID</body></html>', 200)
        ]);

        $olxId = $service->getOfferIdFromUrl($url);

        $this->assertNull($olxId);
    }
}
