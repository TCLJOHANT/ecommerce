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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('grand_total', 20, 2)->nullable(); //GRAN TOTAL
            $table->string('payment_method')->nullable(); //FORMA DE PAGO
            $table->string('payment_status')->nullable(); //ESTADO DE PAGO
            $table->enum('status', ['new', 'processing','shipped','delivered', 'canceled'])->default('new'); //ESTADO
            $table->string('currency')->nullable(); //Divisa
            $table->decimal('shipping_amount', 20, 2)->nullable(); //PRECIO DE ENVIO
            $table->string('shipping_method')->nullable(); //meetod de envio
            $table->text('notes')->nullable(); //notas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
