<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdemServico;

class MaoDeObra extends Model
{
    protected $table = 'maodeobra';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'tipo',
        'valor',
        'obs',
        'os_permitida',
    ];

    
    public function ordemServico() {
        return $this->belongsTo(OrdemServico::class, 'id_os');
    }
}
