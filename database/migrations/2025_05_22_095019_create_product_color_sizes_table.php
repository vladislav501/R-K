<?php
        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        class CreateProductColorSizesTable extends Migration
        {
            public function up()
            {
                Schema::create('product_color_sizes', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('product_id')->constrained()->onDelete('cascade');
                    $table->foreignId('color_id')->constrained()->onDelete('cascade');
                    $table->foreignId('size_id')->constrained()->onDelete('cascade');
                    $table->integer('quantity')->default(0);
                    $table->timestamps();
                });
            }

            public function down()
            {
                Schema::dropIfExists('product_color_sizes');
            }
        }