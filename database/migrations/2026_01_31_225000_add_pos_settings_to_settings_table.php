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
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('default_tax_rate', 5, 2)->default(0)->after('site_copyright');
            $table->decimal('default_discount_rate', 5, 2)->default(0)->after('default_tax_rate');
            $table->text('receipt_thank_you_message')->nullable()->after('default_discount_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['default_tax_rate', 'default_discount_rate', 'receipt_thank_you_message']);
        });
    }
};
