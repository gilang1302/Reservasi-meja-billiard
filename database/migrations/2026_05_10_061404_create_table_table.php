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
        Schema::create('table', function (Blueprint $table) {
            $table->string('id', 11)->primary(); // Sesuai diagram
            $table->string('table_number', 5);
            $table->enum('status', ['Available', 'Occupied', 'Booked', 'Maintenance'])->default('Available');
            $table->decimal('price_per_hour', 10, 2)->default(30000);
            $table->decimal('price_peak_per_hour', 10, 2)->default(45000); // Harga peak hour
            $table->enum('lamp_status', ['on', 'off'])->default('off');
            $table->integer('position_x')->default(0); // Posisi untuk denah
            $table->integer('position_y')->default(0); // Posisi untuk denah
            $table->string('table_type', 30)->default('Standard'); // Standard, VIP, Tournament
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table');
    }
};
