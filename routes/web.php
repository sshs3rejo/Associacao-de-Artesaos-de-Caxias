<?php

use App\Http\Controllers\Admin\AdminArtisanController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoriaProdutoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\VendaController;
use App\Http\Controllers\Admin\CompraMateriaPrimaController;
use App\Http\Controllers\Admin\MateriaPrimaController;
use App\Http\Controllers\Admin\ContatoController as AdminContatoController;
use App\Http\Controllers\Admin\FornecedorController;
use App\Http\Controllers\Admin\InscricaoOficinaController;
use App\Http\Controllers\Admin\InstrutorController;
use App\Http\Controllers\Admin\OficinaController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InscricaoController;

use App\Http\Controllers\PaginaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ContatoController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Rotas de Autenticação
Route::get('/login', [AuthController::class, 'index'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');
Route::post('/register', [AuthController::class, 'store'])->middleware('throttle:3,10')->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Rota Padrão
Route::get('/', [PaginaController::class, 'home'])->name('home');

// Health Check
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});

// Rotas Públicas
Route::get('/home', fn () => redirect()->route('home'));
Route::get('/sobre', [PaginaController::class, 'sobre'])->name('sobrenos');
Route::get('/contato', [PaginaController::class, 'contato'])->name('contato');
Route::post('/contato', [ContatoController::class, 'store'])->middleware('throttle:5,10')->name('contato.store');

// Rotas Públicas de Visualização
Route::get('/eventos', [EventoController::class, 'index'])->name('evento');
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos');

// Inscrição em Eventos (público, precisa de login)
Route::middleware(['auth', 'throttle:20,1'])->group(function () {
    Route::post('/eventos/{evento}/inscrever', [InscricaoController::class, 'store'])->name('eventos.inscrever');
    Route::delete('/eventos/{evento}/cancelar-inscricao', [InscricaoController::class, 'destroy'])->name('eventos.cancelar-inscricao');
    Route::post('/carrinho/finalizar', [ProdutoController::class, 'checkout'])->name('carrinho.finalizar');
});

// Tornar-se artesão via perfil
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ArtisanController::class, 'userProfile'])->name('user.perfil');
    Route::post('/perfil/tornar-se-artesao', [ArtisanController::class, 'tornarSePeloPerfil'])->name('user.tornar-se-artesao');
});

// Dashboard do Artesão
Route::middleware(['auth', 'artisan', 'throttle:30,1'])->prefix('artesan')->name('artesan.')->group(function () {
    Route::get('/dashboard', [ArtisanController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/produtos/criar', [ProdutoController::class, 'create'])->name('produtos.criar');
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.salvar');
    Route::get('/produtos/{produto}/editar', [ProdutoController::class, 'edit'])->name('produtos.editar');
    Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.atualizar');
    Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.deletar');
    
    Route::get('/eventos/criar', [EventoController::class, 'create'])->name('eventos.criar');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.salvar');
    Route::get('/eventos/{evento}/editar', [EventoController::class, 'edit'])->name('eventos.editar');
    Route::put('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.atualizar');
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.deletar');
    Route::get('/perfil', [ArtisanController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [ArtisanController::class, 'atualizarPerfil'])->name('perfil.atualizar');
});

// Perfil público do artesão
Route::get('/artesao/{user}', [ArtisanController::class, 'publico'])->name('artesao.publico');

// Rotas Administrativas
Route::middleware(['auth', 'admin', 'throttle:60,1'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminDashboardController::class, 'updateSettings'])->name('admin.settings.update');
    
    // Gestão de Artesãos
    Route::get('/admin/artesao', [AdminArtisanController::class, 'index'])->name('admin.artesao');
    Route::post('/admin/artesao/{user}/aprovar', [AdminArtisanController::class, 'aprovar'])->name('admin.artesao.aprovar');
    Route::post('/admin/artesao/{user}/rejeitar', [AdminArtisanController::class, 'rejeitar'])->name('admin.artesao.rejeitar');
    Route::post('/admin/artesao/{user}/ativar', [AdminArtisanController::class, 'ativar'])->name('admin.artesao.ativar');

    // Gestão de Usuários
    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])->name('admin.usuarios');
    Route::get('/admin/usuarios/create', [AdminUserController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/admin/usuarios', [AdminUserController::class, 'store'])->name('admin.usuarios.store');
    Route::post('/admin/usuarios/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.usuarios.toggle-status');
    Route::post('/admin/usuarios/{user}/change-role', [AdminUserController::class, 'changeRole'])->name('admin.usuarios.change-role');
    Route::delete('/admin/usuarios/{user}', [AdminUserController::class, 'destroy'])->name('admin.usuarios.destroy');

    // Activity Log
    Route::get('/admin/activity-log', [AdminDashboardController::class, 'activityLog'])->name('admin.activity-log');

    // Gestão de Inscrições
    Route::get('/admin/inscricoes', [AdminDashboardController::class, 'inscricoes'])->name('admin.inscricoes');
    Route::delete('/admin/inscricoes/{inscricao}', [AdminDashboardController::class, 'destroyInscricao'])->name('admin.inscricoes.destroy');
    
    // Gestão de Vendas (Confirmar Pagamento do WhatsApp/Pix)
    Route::post('/admin/vendas/{venda}/aprovar', [AdminDashboardController::class, 'aprovarVenda'])->name('admin.vendas.aprovar');
    
    // Gestão de Produtos (Aprovar/Rejeitar Proposta do Artesão)
    Route::post('/admin/produtos/{produto}/aprovar', [AdminDashboardController::class, 'aprovarProduto'])->name('admin.produtos.aprovar');
    Route::post('/admin/produtos/{produto}/rejeitar', [AdminDashboardController::class, 'rejeitarProduto'])->name('admin.produtos.rejeitar');
    
    // Gestão de Eventos (Aprovar/Rejeitar Proposta do Artesão)
    Route::post('/admin/eventos/{evento}/aprovar', [AdminDashboardController::class, 'aprovarEvento'])->name('admin.eventos.aprovar');
    Route::post('/admin/eventos/{evento}/rejeitar', [AdminDashboardController::class, 'rejeitarEvento'])->name('admin.eventos.rejeitar');

    // Gestão de Clientes
    Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('admin.clientes.index');
    Route::get('/admin/clientes/{cliente}', [ClienteController::class, 'show'])->name('admin.clientes.show');

    // Gestão de Vendas
    Route::get('/admin/vendas', [VendaController::class, 'index'])->name('admin.vendas.index');
    Route::get('/admin/vendas/{venda}', [VendaController::class, 'show'])->name('admin.vendas.show');
    Route::delete('/admin/vendas/{venda}', [VendaController::class, 'destroy'])->name('admin.vendas.destroy');

    // Gestão de Contato (submissões do formulário público)
    Route::get('/admin/contatos', [AdminContatoController::class, 'index'])->name('admin.contatos.index');
    Route::get('/admin/contatos/{contato}', [AdminContatoController::class, 'show'])->name('admin.contatos.show');
    Route::delete('/admin/contatos/{contato}', [AdminContatoController::class, 'destroy'])->name('admin.contatos.destroy');

    // Gestão de Instrutores
    Route::get('/admin/instrutores', [InstrutorController::class, 'index'])->name('admin.instrutores.index');
    Route::get('/admin/instrutores/create', [InstrutorController::class, 'create'])->name('admin.instrutores.create');
    Route::post('/admin/instrutores', [InstrutorController::class, 'store'])->name('admin.instrutores.store');
    Route::get('/admin/instrutores/{instrutor}/edit', [InstrutorController::class, 'edit'])->name('admin.instrutores.edit');
    Route::put('/admin/instrutores/{instrutor}', [InstrutorController::class, 'update'])->name('admin.instrutores.update');
    Route::delete('/admin/instrutores/{instrutor}', [InstrutorController::class, 'destroy'])->name('admin.instrutores.destroy');

    // Gestão de categorias (inline no form de produto)
    Route::get('/admin/categorias', [CategoriaProdutoController::class, 'index'])->name('admin.categorias.index');
    Route::post('/admin/categorias', [CategoriaProdutoController::class, 'store'])->name('admin.categorias.store');
    Route::post('/admin/categorias/quick-store', [CategoriaProdutoController::class, 'quickStore'])->name('admin.categorias.quick-store');
    Route::put('/admin/categorias/{categoria}', [CategoriaProdutoController::class, 'update'])->name('admin.categorias.update');
    Route::delete('/admin/categorias/{categoria}', [CategoriaProdutoController::class, 'destroy'])->name('admin.categorias.destroy');

    // Gestão de Fornecedores
    Route::get('/admin/fornecedores', [FornecedorController::class, 'index'])->name('admin.fornecedores.index');
    Route::get('/admin/fornecedores/create', [FornecedorController::class, 'create'])->name('admin.fornecedores.create');
    Route::post('/admin/fornecedores', [FornecedorController::class, 'store'])->name('admin.fornecedores.store');
    Route::get('/admin/fornecedores/{fornecedore}/edit', [FornecedorController::class, 'edit'])->name('admin.fornecedores.edit');
    Route::put('/admin/fornecedores/{fornecedore}', [FornecedorController::class, 'update'])->name('admin.fornecedores.update');
    Route::delete('/admin/fornecedores/{fornecedore}', [FornecedorController::class, 'destroy'])->name('admin.fornecedores.destroy');

    // Gestão de Matérias-Primas
    Route::get('/admin/materias-primas', [MateriaPrimaController::class, 'index'])->name('admin.materias-primas.index');
    Route::get('/admin/materias-primas/create', [MateriaPrimaController::class, 'create'])->name('admin.materias-primas.create');
    Route::post('/admin/materias-primas', [MateriaPrimaController::class, 'store'])->name('admin.materias-primas.store');
    Route::get('/admin/materias-primas/{materiasPrima}/edit', [MateriaPrimaController::class, 'edit'])->name('admin.materias-primas.edit');
    Route::put('/admin/materias-primas/{materiasPrima}', [MateriaPrimaController::class, 'update'])->name('admin.materias-primas.update');
    Route::delete('/admin/materias-primas/{materiasPrima}', [MateriaPrimaController::class, 'destroy'])->name('admin.materias-primas.destroy');

    // Gestão de Compras de Matéria-Prima
    Route::get('/admin/compras-materia-prima', [CompraMateriaPrimaController::class, 'index'])->name('admin.compras-materia-prima.index');
    Route::get('/admin/compras-materia-prima/create', [CompraMateriaPrimaController::class, 'create'])->name('admin.compras-materia-prima.create');
    Route::post('/admin/compras-materia-prima', [CompraMateriaPrimaController::class, 'store'])->name('admin.compras-materia-prima.store');
    Route::get('/admin/compras-materia-prima/{compraMateriaPrima}/edit', [CompraMateriaPrimaController::class, 'edit'])->name('admin.compras-materia-prima.edit');
    Route::put('/admin/compras-materia-prima/{compraMateriaPrima}', [CompraMateriaPrimaController::class, 'update'])->name('admin.compras-materia-prima.update');
    Route::delete('/admin/compras-materia-prima/{compraMateriaPrima}', [CompraMateriaPrimaController::class, 'destroy'])->name('admin.compras-materia-prima.destroy');

    // Gestão de Oficinas
    Route::resource('/admin/oficinas', OficinaController::class)->names('admin.oficinas');

    // Gestão de Inscrições em Oficinas
    Route::get('/admin/inscricoes-oficina', [InscricaoOficinaController::class, 'index'])->name('admin.inscricoes-oficina.index');
    Route::delete('/admin/inscricoes-oficina/{inscricao}', [InscricaoOficinaController::class, 'destroy'])->name('admin.inscricoes-oficina.destroy');

    // ✅ Gestão de Produtos (admin)
    Route::get('/admin/produtos/create', [ProdutoController::class, 'create'])->name('admin.produtos.create');
    Route::post('/admin/produtos', [ProdutoController::class, 'store'])->name('admin.produtos.store');
    Route::get('/admin/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('admin.produtos.edit');
    Route::put('/admin/produtos/{id}', [ProdutoController::class, 'update'])->name('admin.produtos.update');
    Route::delete('/admin/produtos/{id}', [ProdutoController::class, 'destroy'])->name('admin.produtos.destroy');

    // ✅ Aqui também — “create” precisa vir antes de “{id}”
    Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{id}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');
});

// ⚠️ Importante: rota de show deve ficar DEPOIS da “create”
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');


