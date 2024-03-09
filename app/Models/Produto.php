<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'sku',
        'descricao',
        'ncm',
        'cst',
        'letra',
        'pis',
        'confins',
        'pvenda',
        'unidade',
    ];
}
