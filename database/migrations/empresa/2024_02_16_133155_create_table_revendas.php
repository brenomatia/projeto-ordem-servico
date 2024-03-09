<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('revendas', function (Blueprint $table) {
            $table->id();
            $table->string('nome_responsavel')->nullable();
            $table->string('nome_empresa')->unique()->nullable(); 
            $table->string('cnpj_empresa')->unique()->nullable();
            $table->string('numero')->nullable(); 
            $table->string('desconto')->nullable(); 
            $table->string('endereco')->nullable(); 
            $table->string('cep')->nullable(); 
            $table->string('email')->nullable(); 
            $table->string('celular')->nullable();
            $table->string('obs')->nullable(); 
            $table->timestamps();
        });
    }

};
