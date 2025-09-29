<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OlxConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;

    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function build()
    {
        $url = url('/olx/confirm/' . $this->subscriber->confirmation_token);

        return $this->subject('Подтверждение подписки на изменение цены')
                    ->view('emails.confirm_subscription')
                    ->with([
                        'url' => $url,
                    ]);
    }
}
