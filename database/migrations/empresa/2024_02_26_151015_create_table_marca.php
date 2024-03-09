<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('marca')->nullable();
            $table->string('equipamento')->nullable();
            $table->string('id_equipamento')->nullable();
            $table->timestamps();
        });
    }

};
