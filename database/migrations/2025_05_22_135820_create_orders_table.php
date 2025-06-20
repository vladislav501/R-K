<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->string('status');
            $table->string('delivery_method');
            $table->string('delivery_address')->nullable();
            $table->foreignId('pickup_point_id')->nullable()->constrained('pickup_points')->onDelete('set null');
            $table->timestamp('order_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};