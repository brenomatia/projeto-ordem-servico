<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EquipamentoOS;
use App\Models\OrdemServico;

class CarrinhoOrdem extends Model
{
    protected $table = 'carrinho_ordem';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_equipamento',
        'cod_os',
        'nome_cliente',
        'tipo_pagamento',
        'valorTotal',
        'desconto',
        'valorComDesconto',
        'valorPago',
        'valorTroco',
        'qtdParcelas',
        'valorParcelas',
    ];
    
    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'cod_os', 'id');
    }
    public function equipamentoOS()
    {
        return $this->belongsTo(EquipamentoOS::class, 'id_equipamento', 'id');
    }
}
