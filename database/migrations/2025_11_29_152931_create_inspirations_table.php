<?php

use App\Enums\InspirationCategoryEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspirations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image_url');
            $table->enum('category', array_map(fn ($case) => $case->value, InspirationCategoryEnum::cases()))
                ->default(InspirationCategoryEnum::All->value);
            $table->integer('display_order')->default(0);
            $table->enum('height', ['short', 'medium', 'tall'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspirations');
    }
};
