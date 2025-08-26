<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlunoAuthController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlunoPublicoController;
use App\Http\Controllers\PublicDashboardController;
use App\Http\Controllers\ComparativoPublicoController;
use App\Http\Controllers\ComparativoGraficoController;
use App\Http\Middleware\CheckAnySession;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\CheckAlunoSession;

/*
|--------------------------------------------------------------------------
| Landing Page Pública
|--------------------------------------------------------------------------
*/
Route::view('/', 'public.home')->name('public.home');

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação (Admin/Técnico)
|--------------------------------------------------------------------------
*/
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação (Atleta)
|--------------------------------------------------------------------------
*/
Route::get('/aluno/login',  [AlunoAuthController::class, 'showLogin'])->name('aluno.login');
Route::post('/aluno/login', [AlunoAuthController::class, 'login'])->name('aluno.login.post');
Route::post('/aluno/logout',[AlunoAuthController::class, 'logout'])->name('aluno.logout');

/*
|--------------------------------------------------------------------------
| Dashboard do Aluno (só quem tiver sessão de atleta)
|--------------------------------------------------------------------------
*/
Route::get('/aluno/dashboard', [AlunoController::class, 'dashboard'])
     ->middleware(CheckAlunoSession::class)
     ->name('aluno.dashboard');

/*
|--------------------------------------------------------------------------
| Rotas que TODOS os logados (admin | técnico | atleta) podem acessar
|--------------------------------------------------------------------------
*/
Route::middleware([ CheckAnySession::class, 'throttle:20,1' ])
     ->group(function () {

    // Dashboard genérico
    Route::get('/public/dashboard', [PublicDashboardController::class, 'index'])
         ->name('public.dashboard');

    // Visão de Análise
    Route::get('/analise', [AlunoPublicoController::class, 'index'])
         ->name('analise.index');

    Route::get('/analise/aluno/{matricula}', [AlunoPublicoController::class, 'mostrar'])
         ->name('analise.mostrar')
         ->middleware('throttle:60,1');

    // Comparativos
    Route::get('/comparar', [ComparativoPublicoController::class, 'index'])
         ->name('comparar.index');

    Route::post('/comparar', [ComparativoPublicoController::class, 'narrar'])
         ->name('comparar.narrar')
         ->middleware('throttle:20,1');

    Route::get('/comparar/grafico', [ComparativoGraficoController::class, 'index'])
         ->name('comparar.grafico.index');

    Route::post('/comparar/grafico/dados', [ComparativoGraficoController::class, 'comparar'])
         ->name('comparar.grafico.dados')
         ->middleware('throttle:20,1');
    Route::get('/analise/instituicao/{instituicao}/alunos',[AlunoPublicoController::class, 'listarPorInstituicao'])->name('analise.alunos')->middleware('throttle:60,1');
});


/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Técnico)
|--------------------------------------------------------------------------
*/
Route::middleware([ CheckSession::class ])
     ->group(function () {
    Route::get('/alunos/cadastrados', [AlunoController::class, 'index'])
         ->name('aluno.index');

    Route::get('/aluno/create', [AlunoController::class, 'create'])
         ->name('aluno.create');

    Route::post('/aluno', [AlunoController::class, 'store'])
         ->name('aluno.store');

    Route::get('/aluno/{aluno}/edit', [AlunoController::class, 'edit'])
         ->name('aluno.edit');

    Route::put('/aluno/{aluno}', [AlunoController::class, 'update'])
         ->name('aluno.update');

    Route::delete('/aluno/{aluno}', [AlunoController::class, 'destroy'])
         ->name('aluno.destroy');

    Route::get('/aluno/atualizar', [AlunoController::class, 'habilidade'])
         ->name('aluno.updateForm');

    Route::post('/aluno/habilidade/update', [AlunoController::class, 'updateHabilidade'])
         ->name('aluno.habilidade.update');

    Route::get('/aluno/{aluno}/ultima-analise', [AlunoController::class, 'fetchLastAnalysis'])
         ->name('aluno.lastAnalysis');

    Route::get('/aluno/comparativo/{aluno}', [AlunoController::class, 'showComparativo'])
         ->name('aluno.comparativo');

    Route::get('/tecnico/dashboard', [AlunoController::class, 'dashboard'])
         ->name('tecnico.dashboard');
});

/*
|--------------------------------------------------------------------------
| Rotas de Administração (Usuário Admin)
|--------------------------------------------------------------------------
*/
Route::middleware([ CheckSession::class, CheckAdmin::class ])
     ->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))
         ->name('admin.dashboard');

    Route::resource('usuarios', UsuarioController::class)
         ->except(['show']);
});