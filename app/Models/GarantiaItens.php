<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarantiaItens extends Model
{
    protected $table = 'garantiaitens';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'hash',
        'item',
        'qtd',
        'valor',
        'vendedor',
    ];
}
