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
        Schema::create('composition_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('composition_id');
            $table->unsignedBigInteger('ingredient_product_id');
            $table->decimal('quantity', 10, 4);
            $table->enum('unit', ['gram', 'ml', 'piece']);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('composition_id')->references('id')->on('compositions')->onDelete('cascade');
            $table->foreign('ingredient_product_id')->references('id')->on('products')->onDelete('restrict');
            $table->index('composition_id');
            $table->index('ingredient_product_id');
            $table->unique(['composition_id', 'ingredient_product_id'], 'unique_composition_ingredient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition_ingredients');
    }
};

