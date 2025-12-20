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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->text('site_desc');
            $table->string('site_phone');
            $table->text('site_address');
            $table->text('site_email');
            $table->text('email_support');
            $table->text('facebook')->nullable();
            $table->text('x_url')->nullable();
            $table->text('youtube')->nullable();
            $table->text('about_us')->nullable();
            $table->text('meta_desc');
            $table->text('logo');
            $table->text('favicon');
            $table->text('site_copyright');
            $table->text('promotion_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
