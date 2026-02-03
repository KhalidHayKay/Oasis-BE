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
        Schema::create('checkout_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_token')->unique();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('cart_id')->constrained();

            // Only checkout-specific fields
            $table->enum('current_step', ['address', 'summary', 'payment'])->default('address');
            $table->enum('status', [
                'active',
                'expired',
                'converted',
                'requires_attention',
            ])->default('active');
            $table->timestamp('items_captured_at')->nullable();
            $table->timestamp('expires_at');

            // Customer data (will be copied to order)
            $table->string('customer_email')->nullable();
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();

            // Totals
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('shipping_fee', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('currency', 3)->nullable();

            $table->timestamps();
        });

        Schema::create('checkout_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkout_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();

            $table->string('product_name');
            $table->string('product_selected_color');
            $table->text('product_description')->nullable();
            $table->decimal('price_at_checkout', 10, 2);
            $table->integer('quantity')->default(1);

            $table->timestamps();

            $table->index(['checkout_session_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_sessions');
        Schema::dropIfExists('checkout_items');
    }
};
