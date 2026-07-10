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
        Schema::create('transaction', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('booking_id', 11);
            $table->enum('payment_method', ['BCA', 'QRIS', 'Tunai']);
            $table->enum('payment_status', ['Pending', 'Success', 'Failed']);
            $table->string('total_amount', 12);
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
