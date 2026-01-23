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

            $table->string('customer_email')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->json('shipping_address')->nullable();

            $table->enum('status', ['active', 'expired', 'converted',])->default('active');
            $table->enum('current_step', ['checkout', 'address', 'payment', 'summary'])->default('checkout');
            $table->timestamp('expires_at');

            $table->timestamps();

            $table->index(['status', 'expires_at']);
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
