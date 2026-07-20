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
        Schema::create('invoice_extras', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->string('label');
            $table->decimal('value', 15, 2);
            $table->string('type')->default('percentage'); // percentage or fixed
            $table->string('calculation_basis')->default('before_commission'); // before_commission or after_commission
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_extras');
    }
};
