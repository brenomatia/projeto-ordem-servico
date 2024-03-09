<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('vendas_produtos', function (Blueprint $table) {
            $table->id();
            $table->string('hash_transaction')->nullable();
            $table->string('id_user')->nullable();
            $table->string('cod_produto')->nullable();
            $table->string('qtd_produto')->nullable();
            $table->string('nome_cliente')->nullable();
            $table->decimal('valorTotal', 10,2)->nullable();
            $table->string('id_revenda')->nullable();
            $table->timestamps();
        });
    }

};
