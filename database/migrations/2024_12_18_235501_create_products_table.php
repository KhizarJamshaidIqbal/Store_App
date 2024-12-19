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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->text('highlights')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('special_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable()->unique();
            $table->string('shop_sku')->nullable();
            $table->decimal('package_weight', 8, 2)->nullable();
            $table->decimal('package_length', 8, 2)->nullable();
            $table->decimal('package_width', 8, 2)->nullable();
            $table->decimal('package_height', 8, 2)->nullable();
            $table->enum('dangerous_goods', ['none', 'flammable', 'explosive', 'corrosive', 'toxic', 'radioactive', 'liquid'])->default('none');
            $table->string('warranty_type')->nullable();
            $table->string('warranty_period')->nullable();
            $table->boolean('is_draft')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
