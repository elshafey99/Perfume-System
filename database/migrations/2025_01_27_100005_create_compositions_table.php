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
        Schema::create('compositions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            //$table->string('name_en')->nullable();
            $table->string('code')->unique()->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('bottle_size', 10, 2);
            $table->enum('concentration_type', ['EDP', 'EDT', 'Parfum', 'Cologne'])->nullable();
            $table->decimal('base_cost', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->text('instructions')->nullable();
            $table->text('notes')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_magic_recipe')->default(false);
            $table->string('original_perfume_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->index('product_id');
            $table->index('is_magic_recipe');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compositions');
    }
};
