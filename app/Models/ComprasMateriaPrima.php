<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprasMateriaPrima extends Model
{
    protected $table = 'compras_materia_prima';

    protected $primaryKey = 'id_compra';

    protected $fillable = ['id_fornecedor', 'id_materia', 'data_compra', 'quantidade', 'preco_unitario', 'valor_total'];

    protected $casts = [
        'data_compra' => 'date',
        'quantidade' => 'integer',
        'preco_unitario' => 'decimal:2',
        'valor_total' => 'decimal:2',
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedores::class, 'id_fornecedor');
    }

    public function materiaPrima()
    {
        return $this->belongsTo(MateriasPrimas::class, 'id_materia');
    }
}
