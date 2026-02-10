<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Cliente extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco',
        'cep',
        'senha',
        'cpf_cnpj',
        'pj',
    ];

    protected $hidden = ['senha', 'created_at', 'updated_at'];

    
}
