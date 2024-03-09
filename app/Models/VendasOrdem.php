<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendasOrdem extends Model
{
    protected $table = 'vendas_ordem';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'id_user',
        'cod_os',
        'nome_cliente',
        'desconto',
        'valorTotal',
        'valorPago',
        'tipo_pagamento',
    ];
}
