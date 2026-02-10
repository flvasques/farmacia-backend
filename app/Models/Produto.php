<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
   use HasFactory, SoftDeletes;
   
   protected $table = 'produtos';

   protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'estoque'
    ];

   protected $hidden = ['created_at', 'updated_at'];

   public function categorias()
   {
      return $this->belongsToMany(Categoria::class, 'produtos_categorias', 'produto_id', 'categoria_id');
   }

}
