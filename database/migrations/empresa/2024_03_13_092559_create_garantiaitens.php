<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('garantiaitens', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->nullable();
            $table->string('item')->nullable();
            $table->string('qtd')->nullable();
            $table->string('valor')->nullable();
            $table->string('vendedor')->nullable();
            $table->timestamps();
        });
    }

};
