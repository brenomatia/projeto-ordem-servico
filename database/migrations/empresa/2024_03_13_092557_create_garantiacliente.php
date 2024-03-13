<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('garantiacliente', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->nullable();
            $table->string('vendedor')->nullable();
            $table->string('clienteNome')->nullable();
            $table->string('clienteCelular')->nullable();
            $table->string('clienteEndereco')->nullable();
            $table->string('clienteN')->nullable();
            $table->string('clienteCEP')->nullable();
            $table->date('inicioGarantia')->nullable();
            $table->date('fimGarantia')->nullable();
            $table->timestamps();
        });
    }

};
