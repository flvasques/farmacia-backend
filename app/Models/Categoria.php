<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Categoria extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categorias';

    protected $fillable = ['nome', 'descricao'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public static function getList()
    {
        return self::select('id', 'nome', 'descricao')
            ->orderBy('nome', 'asc')
            ->get()
            ->toArray();
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

}
