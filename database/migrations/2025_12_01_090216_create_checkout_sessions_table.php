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
            $table->enum('status', ['active', 'expired', 'converted'])->default('active');
            $table->enum('current_step', ['address', 'summary', 'payment'])->default('address');
            $table->timestamp('expires_at');

            // Customer data (will be copied to order)
            $table->string('customer_email')->nullable();
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();

            $table->string('stripe_payment_intent_id')->nullable();

            // Totals
            $table->integer('subtotal')->nullable();
            $table->integer('tax')->nullable();
            $table->integer('shipping_fee')->nullable();
            $table->integer('total')->nullable();
            $table->string('currency', 3)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_sessions');
    }
};
