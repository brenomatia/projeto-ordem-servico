<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id';

    use HasFactory;

    protected $fillable = [
        'logo',
        'name',
        'endereco',
        'database_name',
        'database_host',
        'database_port',
        'database_username',
        'database_password',
    ];
}
