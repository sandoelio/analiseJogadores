<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnaliseController;
use App\Http\Controllers\AlunoController;


Route::get('/', function () {
    return redirect('/analise');
});

Route::get('/analise', [AnaliseController::class, 'index']);

Route::get('/alunos/{id}/analises', [AnaliseController::class, 'show']);
Route::get('/aluno/create', [AlunoController::class, 'create'])->name('aluno.create');
Route::post('/aluno', [AlunoController::class, 'store'])->name('aluno.store');
