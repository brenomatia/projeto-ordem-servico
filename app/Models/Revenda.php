<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenda extends Model
{
    protected $table = 'revendas';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'nome_responsavel',
        'nome_empresa',
        'cnpj_empresa',
        'numero',
        'desconto',
        'endereco',
        'cep',
        'email',
        'celular',
        'obs',
    ];
}
