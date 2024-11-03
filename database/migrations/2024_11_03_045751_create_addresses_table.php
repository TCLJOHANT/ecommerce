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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('first_name')->nullable(); //nombre
            $table->string('last_name')->nullable(); //apellido
            $table->string('phone')->nullable(); //telefono
            $table->string('street_address')->nullable(); //direccion
            $table->string('city')->nullable(); //ciudad
            $table->string('state')->nullable(); //estado
            $table->string('zip_code')->nullable(); //codigo postal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
