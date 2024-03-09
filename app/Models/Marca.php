<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'marca',
        'equipamento',
        'id_equipamento',
    ];
}
