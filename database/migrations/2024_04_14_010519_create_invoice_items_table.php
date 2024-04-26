<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->longText('description');
            $table->decimal('unit_price', 10, 2);
            $table->integer('units');
            $table->string('category')->nullable();
            $table->integer('comission');
            $table->string('invoice_id'); 
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreignId('provider_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
