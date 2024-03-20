<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('equipamento_os', function (Blueprint $table) {
            $table->id();
            
            $table->string('equipamento')->nullable();
            $table->string('os_permitida')->nullable();

            $table->string('id_user')->nullable();
            $table->string('listado')->nullable();
            $table->string('status')->nullable();
            $table->string('q_aut')->nullable();

            $table->string('MeioPagamento')->nullable();
            $table->decimal('valorTotal', 10, 2)->nullable();
            $table->string('desconto')->nullable();
            $table->decimal('valorComDesconto', 10, 2)->nullable();
            $table->decimal('valorPago', 10, 2)->nullable();
            $table->decimal('valorTroco', 10, 2)->nullable();
            $table->string('parcelaTotal')->nullable();
            $table->string('valorParcelas')->nullable();


            $table->string('pedidoPecas')->nullable();
            $table->string('entregaPecas')->nullable();
            $table->string('pedidoOBS')->nullable();

            $table->string('substatus')->nullable();
            $table->string('obs_naoautorizado')->nullable();

            $table->timestamps();
        });
    }

};
