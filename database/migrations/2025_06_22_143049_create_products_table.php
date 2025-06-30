<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->boolean('is_offer_pool')->default(false);
            $table->integer('reward_points')->nullable();
            $table->string('image')->nullable();
            $table->string('category')->default('Electronic Devices');
            $table->string('description')->nullable();
            $table->integer('stock')->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Soft delete support
            $table->unique(['name', 'category']);
            $table->unsignedBigInteger('locked_by')->nullable();
            $table->timestamp('locked_at')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['locked_by', 'locked_at']);
        });
    }
};
