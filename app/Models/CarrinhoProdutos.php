<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrinhoProdutos extends Model
{
    protected $table = 'carrinho_produtos';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'id_user',
        'cod_produto',
        'nome_produto',
        'revenda',
        'desconto',
        'qtd',
        'tipo_pagamento',
        'total',
    ];
    
}
