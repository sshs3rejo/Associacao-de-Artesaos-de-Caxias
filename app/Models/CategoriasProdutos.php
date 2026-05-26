<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CategoriasProdutos extends Model
{
    use HasFactory;

    protected $table = 'categorias_produtos';

    protected $primaryKey = 'id_categoria';

    protected $fillable = ['nome_categoria'];

    public static function getAllCached()
    {
        return Cache::remember('categorias_produtos', 3600, function () {
            return self::orderBy('nome_categoria')->get();
        });
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_categoria');
    }
}
