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
    Schema::create('credit_packages', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('price_egp', 8, 2);
        $table->integer('credit_amount');
        $table->integer('reward_points');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_packages');
    }
};
