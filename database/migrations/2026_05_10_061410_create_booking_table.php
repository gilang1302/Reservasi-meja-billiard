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
        Schema::create('booking', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('user_id', 11);
            $table->string('table_id', 11);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('total_price', 10, 2);
            $table->enum('price_type', ['normal', 'peak', 'member_discount'])->default('normal');
            $table->enum('status', ['Pending', 'Confirmed', 'Active', 'Completed', 'Cancelled'])->default('Pending');
            $table->integer('extended_count')->default(0); // berapa kali extend
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('table')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
