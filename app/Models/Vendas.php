<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendas extends Model
{
    use SoftDeletes;
    protected $table = '_vendas';

    protected $primaryKey = 'id_venda';

    protected $fillable = ['id_cliente', 'data_venda', 'valor_total', 'status_pagamento'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function itensVenda()
    {
        return $this->hasMany(ItensVenda::class, 'id_venda');
    }

    public function itens()
    {
        return $this->itensVenda();
    }
}
