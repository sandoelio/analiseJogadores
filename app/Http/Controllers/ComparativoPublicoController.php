<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\Request;

class ComparativoPublicoController extends Controller
{
    /**
     * Exibe o formulário para selecionar dois atletas.
     */
    public function index()
    {
        $instituicoes = Instituicao::with('alunos')
            ->orderBy('nome')
            ->get();

        return view('comparar.index', compact('instituicoes'));
    }

    /**
     * Recebe os IDs dos atletas e gera a narração.
     */
    public function narrar(Request $request)
    {
        $data = $request->validate([
            'aluno1_id' => 'required|exists:alunos,id',
            'aluno2_id' => 'required|exists:alunos,id|different:aluno1_id',
        ], [
            'aluno2_id.different' => 'Você não pode selecionar o mesmo jogador nos dois campos.',
        ]);

        // Carrega atleta 1 e estatísticas
        $aluno1   = Aluno::findOrFail($data['aluno1_id']);
        $analise1 = $aluno1->analises()->latest()->first();
        $stats1   = $analise1?->only(config('comparativo.campos'))
            ?? array_fill_keys(config('comparativo.campos'), 0);

        // Carrega atleta 2 e estatísticas
        $aluno2   = Aluno::findOrFail($data['aluno2_id']);
        $analise2 = $aluno2->analises()->latest()->first();
        $stats2   = $analise2?->only(config('comparativo.campos'))
            ?? array_fill_keys(config('comparativo.campos'), 0);

        // Gera narração e placar
        ['narracao' => $texto, 'placar' => $placar] = $this->gerarNarracao(
            $aluno1->nome,
            $aluno1->instituicao->nome,
            $stats1,
            $aluno2->nome,
            $aluno2->instituicao->nome,
            $stats2
        );

        // Divide em linhas e agrupa em eventos de 4 linhas
        $linhas       = explode("\n\n", $texto);
        $eventos      = array_chunk($linhas, 4);
        $eventosExibe = array_slice($eventos, 0, 10);

        return view('comparar.resultado', [
            'aluno1'  => $aluno1,
            'aluno2'  => $aluno2,
            'eventos' => $eventosExibe,
            'placar'  => $placar,
        ]);
    }

    /**
     * Simula o duelo 1×1 e retorna texto e placar.
     *
     * @return array{ narracao: string, placar: array<string,int> }
     */
    private function gerarNarracao(
        string $n1,
        string $i1,
        array  $s1,
        string $n2,
        string $i2,
        array  $s2
    ): array {
        $randFloat = fn() => mt_rand() / mt_getrandmax();
        $randItem  = fn(array $arr) => $arr[array_rand($arr)];
        $tpl       = config('comparativo.templates');
        $maxEvents = config('comparativo.eventos');

        $placar   = [$n1 => 0, $n2 => 0];
        $narracao = [
            "🏀 Início do mano a mano: {$n1} ({$i1}) vs {$n2} ({$i2})"
        ];

        for ($e = 0; $e < $maxEvents; $e++) {
            if ($e % 2 === 0) {
                [$at, $ast, $df, $dfst] = [$n1, $s1, $n2, $s2];
            } else {
                [$at, $ast, $df, $dfst] = [$n2, $s2, $n1, $s1];
            }

            $den   = $ast['dominio'] + $ast['jogada'] + $dfst['marcacao'];
            $pKeep = $den > 0
                ? ($ast['dominio'] + $ast['jogada']) / $den
                : 0;

            if ($randFloat() > $pKeep) {
                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$at, $df],
                    $randItem($tpl['dribles'])
                ) . ", mas {$df} rouba a bola.";

                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$at, $df],
                    $randItem($tpl['rebotes'])
                );

                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$at, $df],
                    $randItem($tpl['transicoes'])
                );

                continue;
            }

            $narracao[] = str_replace(
                ['{at}', '{df}'],
                [$at, $df],
                $randItem($tpl['dribles'])
            ) . " e parte para o ataque.";

            $template = $randItem($tpl['finishes']);
            if ($randFloat() * 100 <= $ast['finalizacao']) {
                $placar[$at] += 2;
                $narracao[] = str_replace('{at}', $at, $template)
                    . " — *cesta!* +2 pontos.";
            } else {
                $narracao[] = $randItem($tpl['misses']) . ".";
                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$at, $df],
                    $randItem($tpl['rebotes'])
                );
                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$at, $df],
                    $randItem($tpl['transicoes'])
                );
            }
        }

        $narracao[] = "⏱️ Fim do duelo: {$n1} {$placar[$n1]} x {$placar[$n2]} {$n2}";
        if ($placar[$n1] > $placar[$n2]) {
            $narracao[] = "🏆 Vitória de {$n1}!";
        } elseif ($placar[$n1] < $placar[$n2]) {
            $narracao[] = "🏆 Vitória de {$n2}!";
        } else {
            $narracao[] = "🤝 Empate dramático!";
        }

        return [
            'narracao' => implode("\n\n", $narracao),
            'placar'   => $placar,
        ];
    }
}
