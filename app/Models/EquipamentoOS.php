<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Carrinho;
use App\Models\Terceiro;
use App\Models\MaoDeObra;
class EquipamentoOS extends Model
{
    protected $table = 'equipamento_os';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'equipamento',
        'os_permitida',

        'id_user',
        'listado',
        'status',
        'q_aut',
        
        'valor_autorizado',
        'valor_final_autorizado',
        'valor_pago_autorizado',
        'tipo_pagamento_autorizado',

        'aguardando_pcs_data',
        'aguardando_pcs_obs',

        'data_compra_garantia',
        'vendido_por_garantia',
        'defeito_garantia',
        'nfe_garantia',
        'uso_profissional_garantia',
    ];

    public function carrinhos()
    {
        return $this->hasMany(Carrinho::class, 'id_equipamento_permitido', 'id');
    }
    public function maodeobra()
    {
        return $this->hasMany(MaoDeObra::class, 'id_equipamento_permitido', 'id');
    }
    public function terceiro()
    {
        return $this->hasMany(Terceiro::class, 'id_equipamento_permitido', 'id');
    }
    public function ordemServico()
    {
        return $this->belongsTo(OrdemServico::class, 'os_permitida', 'id');
    }
}
