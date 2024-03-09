<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up(): void
    {
        Schema::create('produtos_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('hash_transaction')->nullable();
            $table->string('id_user')->nullable();
            $table->string('desconto_porcentagem')->nullable();
            $table->decimal('valorTotal', 10,2)->nullable();
            $table->decimal('valorPago', 10,2)->nullable();
            $table->string('parcelas')->nullable();
            $table->string('tipo_pagamento')->nullable();
            $table->timestamps();
        });
    }


};
