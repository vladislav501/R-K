<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupPointsTable extends Migration
{
    public function up()
    {
        Schema::create('pickup_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('hours');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pickup_points');
    }
}