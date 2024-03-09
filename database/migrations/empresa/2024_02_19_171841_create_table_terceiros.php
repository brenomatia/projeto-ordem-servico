<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('terceiros', function (Blueprint $table) {
            $table->id();
            $table->string('nome_empresa')->nullable();
            $table->string('tipo_servico')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->string('obs')->nullable();
            $table->string('id_os')->nullable();
            $table->string('id_equipamento_permitido')->nullable();
            $table->timestamps();
        });
    }


};
