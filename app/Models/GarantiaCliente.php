<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GarantiaItens;

class GarantiaCliente extends Model
{
    protected $table = 'garantiacliente';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'hash',
        'vendedor',
        'clienteNome',
        'clienteCelular',
        'clienteEndereco',
        'clienteN',
        'clienteCEP',
        'inicioGarantia',
        'fimGarantia',
    ];

    public function garantiaItens()
    {
        return $this->hasMany(GarantiaItens::class, 'hash', 'hash');
    }
}
