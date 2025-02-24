<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('special_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->string('image_path')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['active', 'inactive', 'scheduled'])->default('inactive');
            $table->boolean('is_featured')->default(false);
            $table->json('applicable_products')->nullable(); // Store product IDs
            $table->json('terms_conditions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('special_offers');
    }
};
