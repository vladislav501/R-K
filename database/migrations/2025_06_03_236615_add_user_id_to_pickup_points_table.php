<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPickupPointsTable extends Migration
{
    public function up()
    {
        Schema::table('pickup_points', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('hours');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pickup_points', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}