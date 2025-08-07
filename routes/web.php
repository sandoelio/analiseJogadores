<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlunoPublicoController;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\CheckAdmin;

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
Route::get('/',[AlunoPublicoController::class, 'index'])->name('analise.index');

Route::get('/analise/instituicao/{instituicao}/alunos',[AlunoPublicoController::class,'listarPorInstituicao']
)->name('analise.alunos');

Route::get('/analise/aluno/{matricula}',[AlunoPublicoController::class, 'mostrar'])->name('analise.mostrar');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Usuário Autenticado)
|--------------------------------------------------------------------------
*/
Route::middleware(CheckSession::class)->group(function () {

    Route::get('/aluno/dashboard', [AlunoController::class, 'dashboard'])->name('aluno.dashboard');

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

    Route::get('/usuarios',[UsuarioController::class, 'index'])->name('usuarios.index');

    Route::get('/usuarios/create',[UsuarioController::class, 'create'])->name('usuarios.create');

    Route::post('/usuarios',[UsuarioController::class, 'store'])->name('usuarios.store');

    Route::get('/usuarios/{usuario}/edit', [UsuarioController::class,'edit'])->name('usuarios.edit');

    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');

    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});
