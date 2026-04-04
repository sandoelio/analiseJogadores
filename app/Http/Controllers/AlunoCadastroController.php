<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlunoStoreRequest;
use App\Http\Requests\AlunoUpdateRequest;
use App\Models\Aluno;
use App\Models\AlunoHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlunoCadastroController extends Controller
{
    /**
     * Exibe formulario para inserir aluno e analise.
     */
    public function create()
    {
        return view('aluno.create');
    }

    /**
     * Cria o aluno e registra a primeira analise.
     */
    public function store(AlunoStoreRequest $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $instituicaoId = $user->instituicao_id;
        $data = $request->validated();

        $jaCadastrado = Aluno::where('nome', $data['nome'])
            ->where('user_id', $userId)
            ->where('instituicao_id', $instituicaoId)
            ->exists();

        if ($jaCadastrado) {
            return back()
                ->withErrors(['nome' => 'Este atleta ja esta cadastrado.'])
                ->withInput();
        }

        $sigla = strtoupper(substr($user->instituicao->nome, 0, 3));
        $uid = Str::random(7);
        $matricula = "{$sigla}-{$uid}";

        $sexo = null;
        if (!empty($data['sexo'])) {
            $sexoInformado = $data['sexo'];

            if (in_array($sexoInformado, ['Masculino', 'M'], true)) {
                $sexo = 'Masculino';
            } elseif (in_array($sexoInformado, ['Feminino', 'F'], true)) {
                $sexo = 'Feminino';
            }
        }

        $idade = null;
        if (!empty($data['data_nascimento'])) {
            try {
                $idade = Carbon::parse($data['data_nascimento'])->age;
                if ($idade < 0) {
                    $idade = null;
                }
            } catch (\Exception $exception) {
                $idade = null;
            }
        }

        $aluno = Aluno::firstOrCreate(
            [
                'nome' => $data['nome'],
                'user_id' => $userId,
                'instituicao_id' => $instituicaoId,
            ],
            [
                'matricula' => $matricula,
                'data_nascimento' => $data['data_nascimento'] ?? null,
                'sexo' => $sexo,
                'idade' => $idade,
                'telefone' => $data['telefone'] ?? null,
            ]
        );

        $aluno->analises()->create([
            'arremesso' => $data['arremesso'],
            'passe' => $data['passe'],
            'marcacao' => $data['marcacao'],
            'bandeja' => $data['bandeja'],
            'rebote' => $data['rebote'],
            'dominio' => $data['dominio'],
            'potencia_mmss' => $data['potencia_mmss'],
            'capacidade_aerobica' => $data['capacidade_aerobica'],
            'agilidade' => $data['agilidade'],
            'flexibilidade' => $data['flexibilidade'],
            'potencia_mmii' => $data['potencia_mmii'],
            'massa_corporal_kg' => $data['massa_corporal_kg'],
            'gordura_pct' => $data['gordura_pct'],
            'massa_magra_pct' => $data['massa_magra_pct'],
            'envergadura_cm' => $data['envergadura_cm'],
            'imc' => $data['imc'],
            'problema_saude' => $data['problema_saude'],
            'atestado_valido' => $data['atestado_valido'],
            'usa_medicacao' => $data['usa_medicacao'],
        ]);

        return redirect()
            ->route('aluno.create')
            ->with('success', "Analise registrada para {$aluno->nome} (Matricula: {$aluno->matricula}).");
    }

    /**
     * Exibe o formulario para editar apenas o nome do aluno.
     */
    public function edit(Aluno $aluno)
    {
        $this->authorize('update', $aluno);

        return view('aluno.edit', compact('aluno'));
    }

    /**
     * Atualiza somente o nome do aluno.
     */
    public function update(AlunoUpdateRequest $request, Aluno $aluno)
    {
        $this->authorize('update', $aluno);
        $data = $request->validated();

        $aluno->update([
            'nome' => $data['nome'],
        ]);

        return redirect()
            ->route('aluno.index')
            ->with('success', "Nome do aluno atualizado para \"{$aluno->nome}\".");
    }

    /**
     * Remove o aluno definitivamente.
     */
    public function destroy(Aluno $aluno)
    {
        $this->authorize('delete', $aluno);

        DB::transaction(function () use ($aluno) {
            AlunoHistory::create([
                'aluno_id' => $aluno->id,
                'evento' => 'deleted',
                'dados' => ['motivo' => 'exclusao manual'],
                'changed_by' => Auth::id(),
            ]);

            \App\Models\Analise::where('aluno_id', $aluno->id)->delete();
            $aluno->delete();
        });

        return redirect()
            ->route('aluno.index')
            ->with('success', "Aluno \"{$aluno->nome}\" excluido com sucesso.");
    }
}
