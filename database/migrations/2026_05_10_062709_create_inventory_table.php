<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('item_name', 100);
            $table->enum('category', ['Cue', 'Ball', 'Chalk', 'Rack', 'Accessory'])->default('Cue');
            $table->integer('quantity')->default(1);
            $table->integer('quantity_available')->default(1);
            $table->enum('status', ['Good', 'Damaged', 'Lost'])->default('Good');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('rental_price_per_hour', 10, 2)->default(5000);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
