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
        // Adicionar validação personalizada ou mais descritiva
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'arremesso' => 'required|numeric|between:0,100',
            'passe' => 'required|numeric|between:0,100',
            'marcacao' => 'required|numeric|between:0,100',
            'finalizacao' => 'required|numeric|between:0,100',
            'jogada' => 'required|numeric|between:0,100',
            'dominio' => 'required|numeric|between:0,100',
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'between' => 'O campo :attribute deve estar entre 0 e 100.',
            'max' => 'O campo :attribute não pode ter mais que :max caracteres.'
        ]);

        try {
            // Chama o serviço para inserir o aluno e criar a análise
            $aluno = $this->alunoService->storeAlunoComAnalise($validated);

            if ($aluno) {
                // Redirecionamento com sucesso
                return redirect()->route('aluno.create')
                    ->with('success', "Aluno {$aluno->nome} cadastrado com sucesso!");
            } else {
                // Tratamento de erro para caso $aluno seja null
                return redirect()->route('aluno.create')
                    ->with('error', 'Erro ao cadastrar aluno: O retorno foi inválido.')
                    ->withInput();
            }
        } catch (\Exception $e) {
            // Tratamento de erro
            return redirect()->route('aluno.create')
                ->with('error', 'Erro ao cadastrar aluno: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Adicionar outros métodos úteis

    // public function update(Request $request, $id)
    // {
    //     // Similar validation as store method
    //     $validated = $request->validate([
    //         'nome' => 'required|string|max:255',
    //         'arremesso' => 'required|numeric|between:0,100',
    //         'passe' => 'required|numeric|between:0,100',
    //         'marcacao' => 'required|numeric|between:0,100',
    //         'finalizacao' => 'required|numeric|between:0,100',
    //         'jogada' => 'required|numeric|between:0,100',
    //         'dominio' => 'required|numeric|between:0,100',
    //     ]);

    //     try {
    //         $aluno = $this->alunoService->updateAluno($id, $validated);
    //         return redirect()->route('alunos.show', $id)
    //             ->with('success', "Aluno {$aluno->nome} atualizado com sucesso!");
    //     } catch (\Exception $e) {
    //         return redirect()->route('alunos.edit', $id)
    //             ->with('error', 'Erro ao atualizar aluno: ' . $e->getMessage())
    //             ->withInput();
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $this->alunoService->deleteAluno($id);
    //         return redirect()->route('alunos.index')
    //             ->with('success', 'Aluno excluído com sucesso!');
    //     } catch (\Exception $e) {
    //         return redirect()->route('alunos.index')
    //             ->with('error', 'Erro ao excluir aluno: ' . $e->getMessage());
    //     }
    // }
}
