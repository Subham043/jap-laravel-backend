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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quantity')->default(1)->nullable();
            $table->string('product_name', 500)->nullable();
            $table->string('product_slug', 500)->nullable();
            $table->string('product_description', 500)->nullable();
            $table->string('product_image', 500)->nullable();
            $table->string('product_price', 500)->nullable();
            $table->string('product_dicount', 500)->nullable();
            $table->string('product_weight', 500)->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};

// ALTER TABLE `order_products` ADD `product_image` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `product_dicount`, ADD `product_weight` VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `product_image`;
