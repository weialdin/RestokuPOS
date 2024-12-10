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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Foreign key from order_details
            $table->string('status');
            $table->string('item_name');
            $table->string('customer_first_name');
            $table->string('checkout_link');
            $table->decimal('price', 10, 2); // Total price for the payment
            $table->timestamps();

            // Set the foreign key constraint
            $table->foreign('order_id')->references('order_id')->on('order_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
