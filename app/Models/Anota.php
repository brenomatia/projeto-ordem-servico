<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anota extends Model
{
    protected $table = 'anotacoes';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'obs',
        'os_permitida',
    ];
}
