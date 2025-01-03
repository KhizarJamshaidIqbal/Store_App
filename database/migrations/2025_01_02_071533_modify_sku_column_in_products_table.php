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
        Schema::table('products', function (Blueprint $table) {
            // First drop the existing sku column
            $table->dropColumn('sku');
        });

        Schema::table('products', function (Blueprint $table) {
            // Then add it back as nullable
            $table->string('sku')->nullable()->unique()->after('highlights');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // In case of rollback, drop the nullable sku column
            $table->dropColumn('sku');
        });

        Schema::table('products', function (Blueprint $table) {
            // And add back the original non-nullable column
            $table->string('sku')->unique()->after('highlights');
        });
    }
};
