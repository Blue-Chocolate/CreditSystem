<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->index('category');
            $table->index('is_offer_pool');
            $table->index('price');
            $table->index('created_at');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id');
            $table->index('product_id');
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->index('user_id');
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('package_id');
            $table->index('purchased_at');
        });
    }
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['is_offer_pool']);
            $table->dropIndex(['price']);
            $table->dropIndex(['created_at']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['cart_id']);
            $table->dropIndex(['product_id']);
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['package_id']);
            $table->dropIndex(['purchased_at']);
        });
    }
};
