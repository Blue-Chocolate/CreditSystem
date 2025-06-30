<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditPackagesTable extends Migration {
    public function up(): void {
        Schema::create('credit_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->integer('credits');
            $table->integer('reward_points')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('locked_by')->nullable();
            $table->timestamp('locked_at')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('credit_packages');
        Schema::table('credit_packages', function (Blueprint $table) {
            $table->dropColumn(['locked_by', 'locked_at']);
        });
    }
};
