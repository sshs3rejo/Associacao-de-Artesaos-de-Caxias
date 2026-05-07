<?php

// Update CategoriaSeeder.php
$categoriaPath = 'database/seeders/CategoriaSeeder.php';
$categoriaContent = file_get_contents($categoriaPath);

// Remove Artesanato em Cerâmica (id 3)
$categoriaContent = preg_replace("/\s*\[\s*'id_categoria' => 3,\s*'nome_categoria' => 'Artesanato em Cerâmica',\s*'created_at' => now\(\),\s*'updated_at' => now\(\),\s*\],/s", "", $categoriaContent);

// Rename Bijuterias (id 4)
$categoriaContent = str_replace("'nome_categoria' => 'Bijuterias',", "'nome_categoria' => 'Bijuterias e Biojoias',", $categoriaContent);

// Remove Acessórios (id 6)
$categoriaContent = preg_replace("/\s*\[\s*'id_categoria' => 6,\s*'nome_categoria' => 'Acessórios',\s*'created_at' => now\(\),\s*'updated_at' => now\(\),\s*\],/s", "", $categoriaContent);

file_put_contents($categoriaPath, $categoriaContent);


// Update ProdutoSeeder.php
$produtoPath = 'database/seeders/ProdutoSeeder.php';
$produtoContent = file_get_contents($produtoPath);

// Remove Artesanato em Cerâmica products (lines 68-92 approx)
// Let's use string replacement
$produtoContent = preg_replace("/\s*\/\/ Artesanato em Cerâmica.*?\/\/ Bijuterias/s", "\n\n            // Bijuterias e Biojoias", $produtoContent);

// Update Bijuterias header
$produtoContent = str_replace("// Bijuterias\n", "// Bijuterias e Biojoias\n", $produtoContent);

// Move Acessórios products to category 4
$produtoContent = preg_replace("/\/\/ Acessórios\s*\[/s", "// Acessórios incorporados a Bijuterias e Biojoias\n            [", $produtoContent);
$produtoContent = str_replace("'id_categoria' => 6,", "'id_categoria' => 4,", $produtoContent);

file_put_contents($produtoPath, $produtoContent);
echo "Seeders updated.";
