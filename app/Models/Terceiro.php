<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdemServico;

class Terceiro extends Model
{
    protected $table = 'terceiros';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'nome_empresa',
        'tipo_serviço',
        'valor',
        'obs',
        'ospermited',
    ];
    public function ordemServico() {
        return $this->belongsTo(OrdemServico::class, 'id_os');
    }
}
