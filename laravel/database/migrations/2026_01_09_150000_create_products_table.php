<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Taula products amb camps inspirats en products.json del legacy.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // SKU únic per identificar productes (ex: CPU-AMD-7800X3D)
            $table->string('sku', 50)->unique();
            
            // Nom del producte
            $table->string('name', 255);
            
            // Descripció del producte (nullable per flexibilitat)
            $table->text('description')->nullable();
            
            // Preu amb 2 decimals (màxim 99999999.99)
            $table->decimal('price', 10, 2);
            
            // Estoc disponible
            $table->unsignedInteger('stock')->default(0);
            
            // Ruta imatge del producte (nullable)
            $table->string('image', 255)->nullable();
            
            // Categoria del producte (CPU, GPU, RAM, etc.)
            $table->string('category', 100)->nullable();
            
            // Timestamps automàtics (created_at, updated_at)
            $table->timestamps();
            
            // Índex per cerques freqüents
            $table->index('category');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
