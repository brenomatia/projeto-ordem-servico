<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrinhoOrdem extends Model
{
    protected $table = 'carrinho_ordem';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'id_user',
        'cod_os',
        'cliente_os',
        'revenda',
        'desconto',
        'tipo_pagamento',
        'total',
    ];
    
    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'cod_os', 'id');
    }
}
