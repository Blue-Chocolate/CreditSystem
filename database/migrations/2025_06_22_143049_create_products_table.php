<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('price', 8, 2);
        $table->boolean('is_offer_pool')->default(false);
        $table->integer('reward_points')->nullable(); // Only if offer pool
        $table->string('image')->nullable();
        $table->string('category')->default('Electronic Devices');
        $table->string('image_url')->nullable(); // For external image URLs

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
