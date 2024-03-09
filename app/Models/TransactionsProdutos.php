<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsProdutos extends Model
{
    protected $table = 'produtos_transactions';
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
}
