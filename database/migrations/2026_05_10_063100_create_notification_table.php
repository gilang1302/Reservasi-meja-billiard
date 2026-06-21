<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('user_id', 11);
            $table->string('booking_id', 11)->nullable();
            $table->enum('type', ['booking_reminder', 'booking_confirmed', 'booking_cancelled', 'payment_success', 'extend_time', 'fnb_ready', 'system'])->default('system');
            $table->string('title', 100);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
