<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('ordem_servico', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cliente')->nullable();
            $table->string('id_cliente')->nullable();
            $table->string('abertura_da_ordem')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }


};
