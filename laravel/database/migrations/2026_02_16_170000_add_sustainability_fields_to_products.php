<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Añade campos de sostenibilidad ASG (Ambiental, Social, Gobernanza)
     * para implementar etiquetas eco y criterios de economía circular.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Eco Score: puntuación de sostenibilidad de 0 a 100
            $table->unsignedTinyInteger('eco_score')->default(0)->after('category');
            
            // Producto reacondicionado (economía circular)
            $table->boolean('is_refurbished')->default(false)->after('eco_score');
            
            // Materiales reciclables
            $table->boolean('is_recyclable')->default(false)->after('is_refurbished');
            
            // Embalaje reciclado
            $table->boolean('has_eco_packaging')->default(false)->after('is_recyclable');
            
            // Proveedor local (reduce huella de carbono en transporte)
            $table->boolean('is_local_supplier')->default(false)->after('has_eco_packaging');
            
            // Huella de carbono estimada en kg CO2 (nullable)
            $table->decimal('carbon_footprint', 8, 2)->nullable()->after('is_local_supplier');
            
            // Índices para consultas de sostenibilidad
            $table->index('eco_score');
            $table->index('is_refurbished');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['eco_score']);
            $table->dropIndex(['is_refurbished']);
            
            $table->dropColumn([
                'eco_score',
                'is_refurbished',
                'is_recyclable',
                'has_eco_packaging',
                'is_local_supplier',
                'carbon_footprint',
            ]);
        });
    }
};
