<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update order to link to booking (F&B order per booking)
        Schema::create('order', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('booking_id', 11);
            $table->string('user_id', 11);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('status', ['Pending', 'Processing', 'Ready', 'Delivered', 'Cancelled'])->default('Pending');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
