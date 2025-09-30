<?php

namespace App\Mail;

use App\Models\OlxLink;
use App\Models\OlxPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OlxPriceUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public OlxLink $link;
    public OlxPrice $price;

    /**
     * Create a new message instance.
     */
    public function __construct(OlxLink $link, OlxPrice $price)
    {
        $this->link = $link;
        $this->price = $price;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Ціна на оголошення змінилася!')
            ->markdown('emails.price_updated');
    }
}
