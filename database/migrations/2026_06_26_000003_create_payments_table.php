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
            $table->string('id', 20)->primary(); // e.g. PMT-YYYYMMDD-XXXX
            $table->string('reservation_id', 20);
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['QRIS', 'Transfer Bank', 'Cash']);
            $table->enum('status', ['Pending', 'Success', 'Failed'])->default('Pending');
            $table->dateTime('payment_date');
            $table->text('transaction_details')->nullable(); // Stores QR code strings, virtual accounts, or manual cash details
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
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
