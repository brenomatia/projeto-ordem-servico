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

            $table->decimal('valor_autorizado', 10, 2)->nullable();
            $table->decimal('valor_final_autorizado', 10, 2)->nullable();
            $table->decimal('valor_pago_autorizado', 10, 2)->nullable();
            $table->string('tipo_pagamento_autorizado')->nullable();

            $table->string('aguardando_pcs_data')->nullable();
            $table->string('aguardando_pcs_obs')->nullable();
            $table->string('os_nao_autorizada_obs')->nullable();

            $table->string('data_compra_garantia')->nullable();
            $table->string('vendido_por_garantia')->nullable();
            $table->string('defeito_garantia')->nullable();
            $table->string('nfe_garantia')->nullable();
            $table->string('uso_profissional_garantia')->nullable();

            $table->timestamps();
        });
    }

};
