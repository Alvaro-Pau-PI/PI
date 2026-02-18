<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migración para crear las tablas de pedidos y sus items.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('payment_method')->default('card');
            $table->string('card_last_four', 4)->nullable();
            // Datos de envío
            $table->string('shipping_name');
            $table->string('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_postal', 10);
            $table->string('shipping_phone', 20);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Snapshot del nombre al momento de la compra
            $table->decimal('price', 10, 2); // Snapshot del precio al momento de la compra
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
