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

    public static function getTreeCached()
    {
        return Cache::remember('categorias_tree', 3600, function () {
            return self::parents()->with(['children' => function ($q) {
                $q->orderBy('nome_categoria');
            }])->orderBy('nome_categoria')->get();
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

    public static function getHierarchicalList()
    {
        return Cache::remember('categorias_hierarchical', 3600, function () {
            $result = [];
            $parents = self::parents()->with('children')->orderBy('nome_categoria')->get();
            foreach ($parents as $parent) {
                $result[$parent->id_categoria] = $parent->nome_categoria;
                foreach ($parent->children->sortBy('nome_categoria') as $child) {
                    $result[$child->id_categoria] = '— ' . $child->nome_categoria;
                }
            }
            return $result;
        });
    }

    public static function getGroupedList()
    {
        return Cache::remember('categorias_grouped_list', 3600, function () {
            $result = [];
            $parents = self::parents()->with(['children' => function ($q) {
                $q->orderBy('nome_categoria');
            }])->orderBy('nome_categoria')->get();

            foreach ($parents as $parent) {
                if ($parent->children->isEmpty()) {
                    $result[$parent->id_categoria] = $parent->nome_categoria;
                } else {
                    $subCategories = [];
                    foreach ($parent->children as $child) {
                        $subCategories[$child->id_categoria] = $child->nome_categoria;
                    }
                    $result[$parent->nome_categoria] = $subCategories;
                }
            }
            return $result;
        });
    }
}
