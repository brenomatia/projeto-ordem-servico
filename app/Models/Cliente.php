<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'nome_completo',
        'celular',
        'endereco',
        'cidade',
        'uf',
        'numero',
        'cep',
    ];
}
