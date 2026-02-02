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
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->enum('type', ['payment', 'purchase', 'refund', 'opening_balance']);
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check'])->nullable();
            $table->string('reference_number')->nullable(); // رقم الشيك أو الحوالة
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // المستخدم اللي سجل الدفعة
            $table->timestamps();
            
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index('supplier_id');
            $table->index('type');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
