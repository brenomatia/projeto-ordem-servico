<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Carrinho;
use App\Models\Terceiro;
use App\Models\MaoDeObra;

class OrdemServico extends Model
{
    protected $table = 'ordem_servico';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'nome_cliente',
        'id_cliente',
        'abertura_da_ordem',
        'status',
        'tipo',
    ];

    // Define o relacionamento belongsTo com a model Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
    public function carrinhos() {
        return $this->hasMany(Carrinho::class, 'id_os');
    }

    public function terceiros() {
        return $this->hasMany(Terceiro::class, 'id_os');
    }

    public function maoDeObras() {
        return $this->hasMany(MaoDeObra::class, 'id_os');
    }
    public function equipamentosOS()
    {
        return $this->hasMany(EquipamentoOS::class, 'os_permitida', 'id');
    }

}
