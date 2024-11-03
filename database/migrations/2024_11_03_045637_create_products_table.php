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
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name'); //nombre
            $table->string('slug')->unique(); //slug
            $table->json('images')->nullable(); //imagenes
            $table->longText('description')->nullable(); //descripcion
            $table->decimal('price', 20, 2); //precio
            $table->boolean('is_active')->default(true); //activo
            $table->boolean('is_featured')->default(false); //destacado
            $table->boolean('in_stock')->default(true); //stock
            $table->boolean('on_sale')->default(false); //en venta
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
