<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produto';

    protected $primaryKey = 'id_produto';

    protected $fillable = ['nome', 'descricao', 'preco', 'id_categoria', 'imagem', 'id_artesan', 'is_approved'];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriasProdutos::class, 'id_categoria');
    }

    public function itensVenda()
    {
        return $this->hasMany(ItensVenda::class, 'id_produto');
    }

    public function estoque()
    {
        return $this->hasOne(Estoques::class, 'id_produto');
    }

    public function artisan()
    {
        return $this->belongsTo(User::class, 'id_artesan');
    }
}
