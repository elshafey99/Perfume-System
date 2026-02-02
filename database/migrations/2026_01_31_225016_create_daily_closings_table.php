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
        Schema::create('daily_closings', function (Blueprint $table) {
            $table->id();
            $table->date('closing_date')->unique();
            $table->unsignedBigInteger('closed_by');
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->decimal('total_cash', 15, 2)->default(0);
            $table->decimal('total_card', 15, 2)->default(0);
            $table->integer('total_invoices')->default(0);
            $table->decimal('total_refunds', 15, 2)->default(0);
            $table->decimal('total_expenses', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('closed_by')->references('id')->on('users')->onDelete('restrict');
            $table->index('closing_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_closings');
    }
};
