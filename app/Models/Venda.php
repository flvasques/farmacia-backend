<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'vendas';

    protected $fillable = [
        'cliente_id',
        'total',
        'status',
        'pagamento',
        'observacoes',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items()
    {
        return $this->hasMany(VendaItem::class);
    }
}
