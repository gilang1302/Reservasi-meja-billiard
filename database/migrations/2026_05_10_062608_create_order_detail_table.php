<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_detail', function (Blueprint $table) {
            $table->string('id', 11)->primary();
            $table->string('order_id', 11);
            $table->string('menu_item_id', 11)->nullable();
            $table->string('item_name', 100); // snapshot nama saat order
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // harga satuan saat order
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->foreign('menu_item_id')->references('id')->on('menu_item')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_detail');
    }
};
