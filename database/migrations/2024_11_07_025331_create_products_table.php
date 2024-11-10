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
            $table->string('name');
            $table->string('slug')->nullable();
            $table->bigInteger('category_id');
            $table->bigInteger('city_id');
            $table->bigInteger('menu_id');
            $table->string('code')->unique();
            $table->integer('qty')->nullable();
            $table->integer('size')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->bigInteger('client_id')->nullable();
            $table->boolean('most_populer')->nullable();
            $table->boolean('best_seller')->nullable();
            $table->boolean('status')->default(1);
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
