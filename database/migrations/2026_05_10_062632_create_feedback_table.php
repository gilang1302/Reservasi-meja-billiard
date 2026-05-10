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
        Schema::create('feedback', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('booking_id', 11);
            $table->string('user_id', 11);
            $table->enum('rating', ['1', '2', '3', '4', '5']);
            $table->string('comment', 500)->nullable();
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
