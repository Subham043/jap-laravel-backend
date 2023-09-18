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
        Schema::create('home_page_banners', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image', 500)->nullable();
            $table->string('banner_image_alt', 500)->nullable();
            $table->string('banner_image_title', 500)->nullable();
            $table->boolean('is_active')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_page_banners');
    }
};
