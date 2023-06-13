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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('slug', 500)->unique();
            $table->text('description')->nullable();
            $table->string('price', 500)->default(0)->nullable();
            $table->string('discount', 500)->default(0)->nullable();
            $table->string('inventory', 500)->default(0)->nullable();
            $table->text('featured_image')->nullable();
            $table->string('image_title', 500)->nullable();
            $table->string('image_alt', 500)->nullable();
            $table->string('meta_title', 500)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_new_arrival')->default(1);
            $table->boolean('is_featured')->default(1);
            $table->boolean('is_best_sale')->default(1);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
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
