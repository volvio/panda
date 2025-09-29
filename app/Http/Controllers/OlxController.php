<?php

namespace App\Http\Controllers;

use App\Services\OlxService;
use App\Services\OlxApiService;

class OlxController extends Controller
{
    protected OlxService $olxService;
    protected OlxApiService $olxApi;

    public function __construct(OlxService $olxService, OlxApiService $olxApi)
    {
        $this->olxService = $olxService;
        $this->olxApi = $olxApi;
    }

    /**
     * Отримати ID оголошення OLX за URL-адресою 
     * Приклад запиту:
     * GET /olx/id?url=https://www.olx.ua/d/uk/obyavlenie/xiaomi-15-ultra-16-512-black-vropeyska-versya-IDYrQpq.html
     */
    public function getId()
    {

        $url="https://www.olx.ua/d/uk/obyavlenie/xiaomi-15-ultra-16-512-black-vropeyska-versya-IDYrQpq.html";
        $offerId = $this->olxService->getOfferIdFromUrl($url);
        
        $priceResult = $this->olxApi->getPriceAndCurrency($offerId);

        if ($offerId) {
            return response()->json([
                'success' => true,
                'offer_id' => $offerId,
                'price' => $priceResult['price'],
                'currency' => $priceResult['currency']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Не удалось найти ID объявления на странице.',
        ], 404);
    }
}
