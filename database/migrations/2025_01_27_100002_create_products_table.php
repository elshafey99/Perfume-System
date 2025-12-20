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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            //$table->string('name_en')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->unsignedBigInteger('category_id');
            $table->enum('type', ['ready_made', 'raw_oil', 'alcohol', 'bottle', 'packaging', 'fixative', 'accessory']);
            $table->enum('unit_type', ['piece', 'gram', 'ml', 'tola', 'quarter_tola'])->default('piece');
            $table->decimal('conversion_rate', 10, 4)->default(1);
            $table->decimal('current_stock', 10, 4)->default(0);
            $table->decimal('min_stock_level', 10, 4)->default(0);
            $table->decimal('max_stock_level', 10, 4)->nullable();
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->decimal('price_per_gram', 10, 2)->nullable();
            $table->decimal('price_per_ml', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('is_raw_material')->default(false);
            $table->boolean('is_composition')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('can_return')->default(true);
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->index('category_id');
            $table->index('type');
            $table->index('barcode');
            $table->index('sku');
            $table->index('is_active');
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
