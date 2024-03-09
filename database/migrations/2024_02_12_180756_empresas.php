<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('name');
            $table->string('endereco')->nullable();
            $table->string('database_name');
            $table->string('database_host');
            $table->string('database_port');
            $table->string('database_username');
            $table->string('database_password')->nullable();
            $table->timestamps();
        });
    }

};
