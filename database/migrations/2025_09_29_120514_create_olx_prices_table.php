<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOlxPricesTable extends Migration
{
    public function up(): void
    {
        Schema::create('olx_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('olx_link_id')->constrained('olx_links')->onDelete('cascade');
            $table->decimal('price', 12, 2);
            $table->string('currency', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('olx_prices');
    }
}
