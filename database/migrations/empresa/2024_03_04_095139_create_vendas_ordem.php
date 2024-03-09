<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('vendas_ordem', function (Blueprint $table) {
            $table->id();
            $table->string('id_user')->nullable();
            $table->string('cod_os')->nullable();
            $table->string('nome_cliente')->nullable();
            $table->string('desconto')->nullable();
            $table->decimal('valorTotal', 10,2)->nullable();
            $table->decimal('valorPago', 10,2)->nullable();
            $table->string('parcelas')->nullable();
            $table->string('tipo_pagamento')->nullable();
            $table->timestamps();
        });
    }


};
