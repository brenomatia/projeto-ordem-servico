<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('maodeobra', function (Blueprint $table) {
            $table->id();
            $table->string('tipo')->nullable();
            $table->string('valor')->nullable();
            $table->string('obs')->nullable();
            $table->string('id_os')->nullable();
            $table->string('id_equipamento_permitido')->nullable();
            $table->timestamps();
        });
    }

};
