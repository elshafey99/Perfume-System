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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('composition_id')->nullable();
            $table->string('product_name');
            $table->decimal('quantity', 10, 4);
            $table->enum('unit', ['piece', 'gram', 'ml', 'tola', 'quarter_tola']);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->boolean('is_composition')->default(false);
            $table->boolean('is_custom_blend')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('set null');
            $table->index('sale_id');
            $table->index('product_id');
            $table->index('composition_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};

