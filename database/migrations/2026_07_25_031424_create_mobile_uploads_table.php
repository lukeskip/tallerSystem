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
        Schema::create('mobile_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('cascade');
            $table->foreignId('invoice_item_id')->nullable()->constrained('invoice_items')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_uploads');
    }
};
