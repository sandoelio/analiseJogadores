<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlunoPublicoController;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\CheckAdmin;
use App\Http\Controllers\ComparativoPublicoController;
use App\Http\Controllers\ComparativoGraficoController;
use App\Http\Controllers\PublicDashboardController;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/
Route::get('/login',   [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Página Pública (Seleção de Instituição / Estatísticas)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicDashboardController::class, 'index'])->name('public.dashboard');
Route::get('/analise',[AlunoPublicoController::class, 'index'])->name('analise.index');
Route::get('/comparar', [ComparativoPublicoController::class, 'index'])->name('comparar.index');
Route::get('comparar/grafico', [ComparativoGraficoController::class, 'index'])->name('comparar.grafico.index');

Route::middleware('throttle:60,1')->group(function () {

    Route::get('/analise/instituicao/{instituicao}/alunos',[AlunoPublicoController::class, 'listarPorInstituicao']
    )->name('analise.alunos');
    Route::get('/analise/aluno/{matricula}',[AlunoPublicoController::class, 'mostrar'])->name('analise.mostrar');
});

Route::middleware('throttle:20,1')->group(function () {

    Route::post('/comparar',[ComparativoPublicoController::class, 'narrar'])->name('comparar.narrar');
    Route::post('/comparar/grafico/dados',[ComparativoGraficoController::class, 'comparar'])->name('comparar.grafico.dados');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Usuário Autenticado)
|--------------------------------------------------------------------------
*/
Route::middleware(CheckSession::class)->group(function () {

    Route::get('/alunos/cadastrados', [AlunoController::class, 'index'])->name('aluno.index');
    
    Route::get('/aluno/dashboard', [AlunoController::class, 'dashboard'])->name('aluno.dashboard');

    Route::post('/aluno/habilidade/update', [AlunoController::class, 'updateHabilidade'])->name('aluno.habilidade.update');
    
    Route::get('/aluno/atualizar', [AlunoController::class, 'habilidade'])->name('aluno.updateForm');

    // Rota AJAX para buscar a última análise de um aluno
    Route::get('/aluno/{aluno}/ultima-analise', [AlunoController::class, 'fetchLastAnalysis'])->name('aluno.lastAnalysis');

    Route::get('/aluno/create',[AlunoController::class, 'create'])->name('aluno.create');

    Route::post('/aluno',[AlunoController::class, 'store'])->name('aluno.store');

    Route::get('/aluno/{aluno}/edit', [AlunoController::class, 'edit'])->name('aluno.edit');

    Route::put('/aluno/{aluno}', [AlunoController::class, 'update'])->name('aluno.update');

    Route::delete('/aluno/{aluno}', [AlunoController::class, 'destroy'])->name('aluno.destroy');

    Route::get('/aluno/comparativo/{aluno}',[AlunoController::class, 'showComparativo'])->name('aluno.comparativo');
});

/*
|--------------------------------------------------------------------------
| Rotas de Administração (Usuário Admin)
|--------------------------------------------------------------------------
*/
Route::middleware([CheckSession::class, CheckAdmin::class])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/usuarios',[UsuarioController::class, 'index'])->name('usuarios.index');

    Route::get('/usuarios/create',[UsuarioController::class, 'create'])->name('usuarios.create');

    Route::post('/usuarios',[UsuarioController::class, 'store'])->name('usuarios.store');

    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class,'edit'])->name('usuarios.edit');

    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');

    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});
