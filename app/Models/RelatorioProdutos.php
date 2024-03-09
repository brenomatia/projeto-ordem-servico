<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioProdutos extends Model
{
    protected $table = 'relatorio_produtos';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'hash_transaction',
        'id_user',
        'valorTotal',
        'valorPago',
        'parcelas',
        'desconto_porcentagem',
        'tipo_pagamento',
    ];

    // Define a relação com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
