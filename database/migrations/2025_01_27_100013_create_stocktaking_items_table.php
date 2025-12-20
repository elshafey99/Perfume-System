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
        Schema::create('stocktaking_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stocktaking_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('recorded_stock', 10, 4);
            $table->decimal('actual_stock', 10, 4);
            $table->decimal('difference', 10, 4);
            $table->enum('unit', ['piece', 'gram', 'ml', 'tola', 'quarter_tola']);
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('stocktaking_id')->references('id')->on('stocktakings')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->index('stocktaking_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocktaking_items');
    }
};

