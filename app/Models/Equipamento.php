<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $table = 'equipamentos';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'nome_equipamento',
    ];
}
