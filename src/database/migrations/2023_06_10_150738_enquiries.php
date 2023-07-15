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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('email', 500);
            $table->string('phone', 500);
            $table->string('company_name', 500)->nullable();
            $table->string('company_website', 500)->nullable();
            $table->string('designation', 500)->nullable();
            $table->string('product', 500)->nullable();
            $table->string('quantity', 500)->nullable();
            $table->string('gst', 500)->nullable();
            $table->string('certification', 500)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('alternate_name', 500)->nullable();
            $table->string('alternate_phone', 500)->nullable();
            $table->string('alternate_email', 500)->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
