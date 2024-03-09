<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('carrinho', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('produto')->nullable();
            $table->string('qtd_produto')->nullable();
            $table->string('id_os')->nullable();
            $table->string('id_equipamento_permitido')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->timestamps();
        });
    }

};
