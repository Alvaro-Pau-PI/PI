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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('discount_price', 8, 2)->nullable()->after('price');
            $table->integer('discount_percentage')->nullable()->after('discount_price');
            $table->dateTime('offer_start_date')->nullable()->after('discount_percentage');
            $table->dateTime('offer_end_date')->nullable()->after('offer_start_date');
            $table->boolean('is_offer_active')->default(false)->after('offer_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'discount_price',
                'discount_percentage',
                'offer_start_date',
                'offer_end_date',
                'is_offer_active'
            ]);
        });
    }
};
