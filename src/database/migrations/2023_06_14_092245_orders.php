<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentMode;
use App\Enums\PaymentStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('total_price', 255)->default(0)->nullable();
            $table->string('sub_total', 255)->default(0)->nullable();
            $table->string('coupon_discount', 255)->default(0)->nullable();
            $table->string('total_discount', 255)->default(0)->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('coupon_name', 255)->nullable();
            $table->string('coupon_code', 255)->nullable();
            $table->string('coupon_discount_percentage', 255)->nullable();
            $table->string('coupon_maximum_discount', 255)->nullable();
            $table->string('coupon_maximum_use', 255)->nullable();
            $table->string('billing_first_name', 255)->nullable();
            $table->string('billing_last_name', 255)->nullable();
            $table->string('billing_email', 255)->nullable();
            $table->string('billing_phone', 255)->nullable();
            $table->string('billing_country', 255)->nullable();
            $table->string('billing_state', 255)->nullable();
            $table->string('billing_city', 255)->nullable();
            $table->string('billing_pin', 255)->nullable();
            $table->string('billing_address_1', 255)->nullable();
            $table->string('billing_address_2', 255)->nullable();
            $table->string('shipping_first_name', 255)->nullable();
            $table->string('shipping_last_name', 255)->nullable();
            $table->string('shipping_email', 255)->nullable();
            $table->string('shipping_phone', 255)->nullable();
            $table->string('shipping_country', 255)->nullable();
            $table->string('shipping_state', 255)->nullable();
            $table->string('shipping_city', 255)->nullable();
            $table->string('shipping_pin', 255)->nullable();
            $table->string('shipping_address_1', 255)->nullable();
            $table->string('shipping_address_2', 255)->nullable();
            $table->string('order_notes', 255)->nullable();
            $table->string('receipt', 255)->nullable();
            $table->string('mode_of_payment', 255)->default(PaymentMode::COD->value)->nullable();
            $table->string('order_status', 255)->default(OrderStatus::PROCESSING->value)->nullable();
            $table->string('payment_status', 255)->default(PaymentStatus::PENDING->value)->nullable();
            $table->string('razorpay_signature', 255)->nullable();
            $table->text('razorpay_order_id')->nullable();
            $table->text('razorpay_payment_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
