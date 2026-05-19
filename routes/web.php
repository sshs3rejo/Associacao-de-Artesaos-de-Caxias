<?php

use App\Http\Controllers\Admin\AdminArtisanController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InscricaoController;

use App\Http\Controllers\PaginaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ContatoController;
use Illuminate\Support\Facades\Route;

// Rotas de Autenticação
Route::get('/login', [AuthController::class, 'index'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login');
Route::post('/register', [AuthController::class, 'store'])->middleware('throttle:3,10')->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Rota Padrão
Route::get('/', [PaginaController::class, 'home'])->name('home');

// Rotas Públicas
Route::get('/home', fn () => redirect()->route('home'));
Route::get('/sobre', [PaginaController::class, 'sobre'])->name('sobrenos');
Route::get('/contato', [PaginaController::class, 'contato'])->name('contato');
Route::post('/contato', [ContatoController::class, 'store'])->name('contato.store');

// Rotas Públicas de Visualização
Route::get('/eventos', [EventoController::class, 'index'])->name('evento');
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos');

// Inscrição em Eventos (público, precisa de login)
Route::middleware(['auth'])->group(function () {
    Route::post('/eventos/{evento}/inscrever', [InscricaoController::class, 'store'])->name('eventos.inscrever');
    Route::delete('/eventos/{evento}/cancelar-inscricao', [InscricaoController::class, 'destroy'])->name('eventos.cancelar-inscricao');
});

// Checkout com Mercado Pago
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/{venda}/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/{venda}/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

// Tornar-se artesão via perfil
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ArtisanController::class, 'userProfile'])->name('user.perfil');
    Route::post('/perfil/tornar-se-artesao', [ArtisanController::class, 'tornarSePeloPerfil'])->name('user.tornar-se-artesao');
});

// Dashboard do Artesão
Route::middleware(['auth', 'artisan'])->prefix('artesan')->name('artesan.')->group(function () {
    Route::get('/dashboard', [ArtisanController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/produtos/criar', [ArtisanController::class, 'criarProduto'])->name('produtos.criar');
    Route::post('/produtos', [ArtisanController::class, 'salvarProduto'])->name('produtos.salvar');
    Route::get('/produtos/{produto}/editar', [ArtisanController::class, 'editarProduto'])->name('produtos.editar');
    Route::put('/produtos/{produto}', [ArtisanController::class, 'atualizarProduto'])->name('produtos.atualizar');
    Route::delete('/produtos/{produto}', [ArtisanController::class, 'deletarProduto'])->name('produtos.deletar');
    
    Route::get('/eventos/criar', [ArtisanController::class, 'criarEvento'])->name('eventos.criar');
    Route::post('/eventos', [ArtisanController::class, 'salvarEvento'])->name('eventos.salvar');
    Route::get('/eventos/{evento}/editar', [ArtisanController::class, 'editarEvento'])->name('eventos.editar');
    Route::put('/eventos/{evento}', [ArtisanController::class, 'atualizarEvento'])->name('eventos.atualizar');
    Route::delete('/eventos/{evento}', [ArtisanController::class, 'deletarEvento'])->name('eventos.deletar');
    Route::get('/perfil', [ArtisanController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [ArtisanController::class, 'atualizarPerfil'])->name('perfil.atualizar');
});

// Perfil público do artesão
Route::get('/artesao/{user}', [ArtisanController::class, 'publico'])->name('artesao.publico');

// Rotas Administrativas
Route::middleware(['auth', 'admin'])->group(function () {
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
    Route::post('/admin/usuarios/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.usuarios.toggle-status');
    Route::post('/admin/usuarios/{user}/change-role', [AdminUserController::class, 'changeRole'])->name('admin.usuarios.change-role');
    Route::delete('/admin/usuarios/{user}', [AdminUserController::class, 'destroy'])->name('admin.usuarios.destroy');

    // Gestão de Inscrições
    Route::get('/admin/inscricoes', [AdminDashboardController::class, 'inscricoes'])->name('admin.inscricoes');
    
    // Gestão de Vendas (Confirmar Pagamento do WhatsApp/Pix)
    Route::post('/admin/vendas/{venda}/aprovar', [AdminDashboardController::class, 'aprovarVenda'])->name('admin.vendas.aprovar');
    
    // Gestão de Produtos (Aprovar/Rejeitar Proposta do Artesão)
    Route::post('/admin/produtos/{produto}/aprovar', [AdminDashboardController::class, 'aprovarProduto'])->name('admin.produtos.aprovar');
    Route::post('/admin/produtos/{produto}/rejeitar', [AdminDashboardController::class, 'rejeitarProduto'])->name('admin.produtos.rejeitar');
    
    // Gestão de Eventos (Aprovar/Rejeitar Proposta do Artesão)
    Route::post('/admin/eventos/{evento}/aprovar', [AdminDashboardController::class, 'aprovarEvento'])->name('admin.eventos.aprovar');
    Route::post('/admin/eventos/{evento}/rejeitar', [AdminDashboardController::class, 'rejeitarEvento'])->name('admin.eventos.rejeitar');

    // ✅ Rotas específicas devem vir antes das com {id}
    Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('produtos.create');
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::get('/produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
    Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

    // ✅ Aqui também — “create” precisa vir antes de “{id}”
    Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{id}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');
});

// ⚠️ Importante: rota de show deve ficar DEPOIS da “create”
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');


