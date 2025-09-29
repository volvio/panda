<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOlxLinksTable extends Migration
{
    public function up(): void
    {
        Schema::create('olx_links', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            // olx_id — числовий ID із сайту olx (наприклад 893216480). 
            $table->unsignedBigInteger('olx_id')->nullable()->index();
            // прапор: 0 — ціна не змінювалася, 1 — ціна змінилася (можна використовувати для швидких повідомлень)
            $table->boolean('is_price_update')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('olx_links');
    }
}
