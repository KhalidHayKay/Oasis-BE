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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();

            // Product snapshot
            $table->string('product_name');
            $table->foreignId('product_image_id')->constrained('product_images')->nullable();
            $table->text('product_description')->nullable();
            $table->string('color');
            // Commerce data
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');

            $table->timestamps();

            $table->unique(['cart_id', 'product_id', 'color'], 'unique_cart_item');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
        Schema::dropIfExists('cart_items');
    }
};
