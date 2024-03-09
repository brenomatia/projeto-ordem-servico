<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->nullable();
            $table->string('descricao')->nullable();
            $table->string('ncm')->nullable();
            $table->string('cst')->nullable();
            $table->string('letra')->nullable();
            $table->string('pis')->nullable();
            $table->string('confins')->nullable();
            $table->decimal('pvenda', 10, 2)->nullable();
            $table->string('unidade')->nullable();
            $table->timestamps();
        });
    }

};
