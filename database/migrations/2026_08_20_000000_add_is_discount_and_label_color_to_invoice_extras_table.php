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
        Schema::table('invoice_extras', function (Blueprint $table) {
            $table->boolean('is_discount')->default(false)->after('type');
            $table->string('label_color')->nullable()->after('calculation_basis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_extras', function (Blueprint $table) {
            $table->dropColumn(['is_discount', 'label_color']);
        });
    }
};
