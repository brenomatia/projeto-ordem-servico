<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdemServico;

class Carrinho extends Model
{
    protected $table = 'carrinho';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'sku',
        'produto',
        'qtd_produto',
        'id_equipamento_permitido',
        'total',
    ];

    public function ordemServico() {
        return $this->belongsTo(OrdemServico::class, 'id_os');
    }
}
