<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedores extends Model
{
    protected $table = '_fornecedores';

    protected $primaryKey = 'id_fornecedor';

    protected $fillable = ['nome', 'contato', 'telefone', 'email', 'endereco'];

    public function materiasPrimas()
    {
        return $this->belongsToMany(MateriasPrimas::class, 'compras_materia_prima', 'id_fornecedor', 'id_materia')
            ->withPivot(['data_compra', 'quantidade', 'preco_unitario', 'valor_total'])
            ->withTimestamps();
    }
}
