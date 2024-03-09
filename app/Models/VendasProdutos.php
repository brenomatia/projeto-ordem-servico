<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendasProdutos extends Model
{
    protected $table = 'vendas_produtos';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'hash_transaction',
        'id_user',
        'cod_produto',
        'qtd_produto',
        'nome_cliente',
        'valorTotal',
        'parcelas',
        'id_revenda',
        'tipo_pagamento',
    ];

}
