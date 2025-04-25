<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('brandId')->nullable();
            $table->string('sex')->nullable();
            $table->integer('typeId')->nullable();
            $table->integer('collectionId')->nullable();
            $table->integer('categoryId')->nullable();
            $table->string('article', 10)->unique();
            $table->string('title');
            $table->string('shortTitle');
            $table->text('description');
            $table->string('color')->default('-');
            $table->string('size')->default('-');
            $table->double('price');
            $table->unsignedInteger('imageId')->nullable();
            $table->string('composition')->default('-');
            $table->string('designCountry')->default('-');
            $table->string('manufacturenCountry')->default('-');
            $table->string('importer')->default('-');
            $table->boolean('availability')->default(false);
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
