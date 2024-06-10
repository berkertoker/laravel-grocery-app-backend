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
        Schema::create('product_popularity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('popular_product_id')->constrained('products');
            $table->integer('popularity_score');
            $table->timestamps();
        });
        Schema::create('category_popularity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('popular_category_id')->constrained('categories');
            $table->integer('popularity_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
