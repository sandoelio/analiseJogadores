<?php

namespace App\Http\Controllers;

use App\Services\AlunoService;
use Illuminate\Http\Request;

class AlunoController extends Controller
{

    protected $alunoService;

    public function __construct(AlunoService $alunoService)
    {
        $this->alunoService = $alunoService;
    }

    // Método para exibir o formulário de inserção
    public function create()
    {
        return view('inserirAluno');
    }

   // Método para armazenar aluno e habilidades
   public function store(Request $request)
   {
       $validated = $request->validate([
           'nome' => 'required|string|max:255',
           'arremesso' => 'required|numeric|min:0|max:100',
           'passe' => 'required|numeric|min:0|max:100',
           'marcacao' => 'required|numeric|min:0|max:100',
           'finalizacao' => 'required|numeric|min:0|max:100',
           'jogada' => 'required|numeric|min:0|max:100',
           'dominio' => 'required|numeric|min:0|max:100',
       ]);

       // Chama o serviço para inserir o aluno e criar a análise
       $this->alunoService->storeAlunoComAnalise($validated);

       // Redirecionamento com sucesso
       return redirect()->route('aluno.create')->with('success', 'Cadastrado com sucesso!');
   }
}

