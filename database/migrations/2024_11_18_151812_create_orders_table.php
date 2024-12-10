<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('user_name'); // Nama pengguna
            $table->unsignedBigInteger('table_id')->nullable(); // Relasi dengan table
            $table->string('table_number')->nullable(); // Nomor meja
            $table->enum('payment_type', ['cash', 'card', 'online'])->default('cash'); // Tipe pembayaran
            $table->float('total_amount', 10, 2)->default(0); // Total jumlah
            $table->timestamps(); // Timestamps: created_at, updated_at

            // Foreign key constraint
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
