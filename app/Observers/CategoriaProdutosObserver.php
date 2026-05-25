<?php

namespace App\Observers;

use App\Models\CategoriasProdutos;
use Illuminate\Support\Facades\Cache;

class CategoriaProdutosObserver
{
    public function saved(CategoriasProdutos $categoria): void
    {
        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');
        Cache::forget('categorias_hierarchical');
    }

    public function deleted(CategoriasProdutos $categoria): void
    {
        Cache::forget('categorias_produtos');
        Cache::forget('categorias_produtos_ordered');
        Cache::forget('categorias_hierarchical');
    }
}
