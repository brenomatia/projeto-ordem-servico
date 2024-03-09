<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('carrinho_ordem', function (Blueprint $table) {
            $table->id();
            $table->string('id_user')->nullable();

            $table->string('cod_os')->nullable();
            $table->string('cliente_os')->nullable();

            $table->string('revenda')->nullable();
            $table->string('desconto')->nullable();

            $table->string('tipo_pagamento')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->timestamps();
        });
    }

};
