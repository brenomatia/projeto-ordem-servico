<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo')->unique()->nullable();
            $table->string('celular')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('numero')->nullable();
            $table->string('cep')->nullable();
            $table->timestamps();
        });
    }
};
