<?php

namespace App\Http\Controllers;

use App\Mail\OlxConfirmEmail;
use App\Models\OlxLink;
use App\Models\OlxSubscriber;
use App\Models\OlxPrice;
use App\Services\OlxService;
use App\Services\OlxApiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OlxSubscriptionController extends Controller
{
    protected OlxService $olxService;
    protected OlxApiService $olxApiService;

    public function __construct(OlxService $olxService, OlxApiService $olxApiService)
    {
        $this->olxService = $olxService;
        $this->olxApiService = $olxApiService;
    }

    /**
     * Форма реєстрації
     */
    public function showForm()
    {
        return view('olx.subscribe');
    }

    /**
     * Обробка форми
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'email' => 'required|email'
        ]);

        $url = $request->input('url');
        $email = $request->input('email');

        // Перевіряємо, чи існує така URL
        $link = OlxLink::where('url', $url)->first();
        if ($link) {
            // Перевіряємо, чи є передплатник з цим email на цей URL
            $existingSubscriber = OlxSubscriber::where('olx_link_id', $link->id)
                ->where('email', $email)
                ->first();

            if ($existingSubscriber) {
                return back()->withErrors([
                    'email' => 'Вы уже подписаны на обновление цены для этого объявления.'
                ])->withInput();
            }
        }
        if(!$link) {
            // Отримуємо ID із URL
            $olxId = $this->olxService->getOfferIdFromUrl($url);
            if (!$olxId) {
                return back()->withErrors([
                    'url' => 'Не удалось извлечь ID объявления. Проверьте правильность ссылки.'
                ])->withInput();
            }

            // Отримуємо ціну та валюту
            $priceInfo = $this->olxApiService->getPriceAndCurrency($olxId);
            if (!$priceInfo) {
                return back()->withErrors([
                    'url' => 'Не удалось получить цену для данного объявления'
                ])->withInput();
            }
            
            // Зберігаємо або беремо існуючу URL
            $link = OlxLink::create([
                    'olx_id' => $olxId,
                    'url' => $url, 
                    'is_price_update' => 0
                ]);
             
              // Зберігаємо ціну
            OlxPrice::create([
                'olx_link_id' => $link->id,
                'price' => $priceInfo['price'],
                'currency' => $priceInfo['currency']
            ]);
        }

        // Створюємо передплатника
        $token = Str::uuid();
        $subscriber = OlxSubscriber::create([
            'olx_link_id' => $link->id,
            'email' => $email,
            'confirmation_token' => $token
        ]);
        // Надсилаємо лист підтвердження
        Mail::to($email)->send(new OlxConfirmEmail($subscriber));

        return back()->with('success', 'Проверьте вашу почту и подтвердите подписку.');
    }

    /**
     * Підтвердження email
     */
    public function confirm($token)
    {
        $subscriber = OlxSubscriber::where('confirmation_token', $token)->firstOrFail();
        $subscriber->confirmed_at = Carbon::now();
        $subscriber->save();

        return view('olx.confirmed');
    }
}
