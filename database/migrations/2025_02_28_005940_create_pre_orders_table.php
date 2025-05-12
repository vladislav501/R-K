<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('userId');
            $table->json('products')->nullable();
            $table->string('receivingMethod')->comment('Доставка или Самовывоз');
            $table->string('deliveryAddress')->comment('Адрес доставки или пункта выдачи');
            $table->unsignedInteger('totalSum');
            $table->string('status')->default('Ожидание подтверждения');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_orders');
    }
};