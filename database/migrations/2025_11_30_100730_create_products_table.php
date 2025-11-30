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
            $table->text('description');
            $table->json('price');
            $table->integer('popularity_score');
            $table->integer('rating')->default(0);
            $table->json('colors')->nullable();
            $table->integer('stock');

            $table->foreignId('category_id')->constrained('categories');
            $table->unsignedBigInteger('featured_image_id')->nullable();

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
