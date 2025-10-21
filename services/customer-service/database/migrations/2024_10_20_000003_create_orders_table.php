<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('cart_id')->nullable()->constrained('carts')->onDelete('set null');
            $table->decimal('value', 10, 2);
            $table->decimal('liquid_value', 10, 2);
            $table->unsignedTinyInteger('status_id')->default(1);
            $table->unsignedTinyInteger('payment_status_id')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

