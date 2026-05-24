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

    protected $fillable = ['nome_categoria', 'parent_id'];

    public static function getAllCached()
    {
        return Cache::remember('categorias_produtos', 3600, function () {
            return self::all();
        });
    }

    public static function getOrderedCached()
    {
        return Cache::remember('categorias_produtos_ordered', 3600, function () {
            return self::orderBy('nome_categoria')->get();
        });
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'id_categoria');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id_categoria');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id_categoria');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}
