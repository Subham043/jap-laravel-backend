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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('code', 500)->unique();
            $table->text('description')->nullable();
            $table->string('discount', 500)->default(0)->nullable();
            $table->string('maximum_dicount_in_price', 500)->nullable();
            $table->string('maximum_number_of_use', 500)->nullable();
            $table->boolean('is_active')->default(1);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
