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
            $table->timestamps();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('categories');
            $table->text('description')->nullable();
            $table->text('highlights')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->string('shop_sku')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('texture')->nullable();
            $table->string('color_family')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('pack_type')->nullable();
            $table->string('volume')->nullable();
            $table->float('weight')->nullable();
            $table->string('material')->nullable();
            $table->text('features')->nullable();
            $table->json('express_delivery_countries')->nullable();
            $table->string('brand_classification')->nullable();
            $table->string('shelf_life')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('special_price', 8, 2)->nullable();
            $table->integer('stock');
            $table->float('package_weight')->nullable();
            $table->float('package_length')->nullable();
            $table->float('package_width')->nullable();
            $table->float('package_height')->nullable();
            $table->boolean('dangerous_goods')->default(false);
            $table->boolean('is_draft')->default(false);
            $table->string('status');
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
