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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name');
            $table->text('customer_address');
            $table->string('customer_whatsapp');
            $table->string('customer_email')->nullable();
            $table->text('customer_note')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('service_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->foreignId('voucher_id')->nullable();
            $table->string('voucher_code')->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('delivery');
            $table->enum('status', ['pending', 'process', 'done', 'cancel'])->default('pending');
            $table->enum('transaction_status', ['unpaid', 'waiting', 'paid', 'reject'])->default('unpaid');
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
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
