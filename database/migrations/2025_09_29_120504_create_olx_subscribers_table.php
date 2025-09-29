<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOlxSubscribersTable extends Migration
{
    public function up(): void
    {
        Schema::create('olx_subscribers', function (Blueprint $table) {
            $table->id();
            // зовнішній ключ на таблицю olx_links
            $table->foreignId('olx_link_id')->constrained('olx_links')->onDelete('cascade');
            // email передплатника (обов'язкове поле)
            $table->string('email');
            // необов'язкові поля для підтвердження email 
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmation_token')->nullable();
            $table->timestamps();

            $table->unique(['olx_link_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('olx_subscribers');
    }
}
