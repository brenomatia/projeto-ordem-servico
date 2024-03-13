<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produto;
use App\Models\GarantiaItens;

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

    // Define o relacionamento com o modelo Produto
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'cod_produto', 'sku');
    }
    public function garantiacliente()
    {
        return $this->hasOne(GarantiaCliente::class, 'hash', 'hash_transaction');
    }
}
