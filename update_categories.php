<?php
use Illuminate\Support\Facades\DB;

// Rename 4
DB::table('categorias_produtos')->where('id_categoria', 4)->update(['nome_categoria' => 'Bijuterias e Biojoias']);

// Move products from 6 to 4
DB::table('produto')->where('id_categoria', 6)->update(['id_categoria' => 4]);

// Delete 6
DB::table('categorias_produtos')->where('id_categoria', 6)->delete();

// Delete products from 3, then category 3
DB::table('produto')->where('id_categoria', 3)->delete();
DB::table('categorias_produtos')->where('id_categoria', 3)->delete();

echo "Database categories updated.\n";
