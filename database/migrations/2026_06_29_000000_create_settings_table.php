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
            $table->string('shop_name')->default('POS System');
            $table->string('shop_email')->nullable();
            $table->string('shop_phone')->nullable();
            $table->text('shop_address')->nullable();
            $table->string('currency_symbol')->default('$');
            $table->string('currency_position')->default('before'); // 'before' or 'after'
            $table->decimal('vat_percentage', 5, 2)->default(0.00);
            $table->string('shop_logo')->nullable();
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
