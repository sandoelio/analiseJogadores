<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\Request;

class ComparativoPublicoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::with('alunos')->orderBy('nome')->get();
        return view('comparar.index', compact('instituicoes'));
    }

    public function narrar(Request $request)
    {
        $data = $request->validate([
            'aluno1_id' => 'required|exists:alunos,id',
            'aluno2_id' => 'required|exists:alunos,id|different:aluno1_id',
        ], [
            'aluno2_id.different' => 'Você não pode selecionar o mesmo jogador nos dois campos.',
        ]);

        $aluno1   = Aluno::findOrFail($data['aluno1_id']);
        $analise1 = $aluno1->analises()->latest()->first();
        $stats1   = $analise1
            ? $analise1->only(config('comparativo.campos'))
            : array_fill_keys(config('comparativo.campos'), 0);

        $aluno2   = Aluno::findOrFail($data['aluno2_id']);
        $analise2 = $aluno2->analises()->latest()->first();
        $stats2   = $analise2
            ? $analise2->only(config('comparativo.campos'))
            : array_fill_keys(config('comparativo.campos'), 0);

        ['narracao' => $texto, 'placar' => $placar] = $this->gerarNarracao(
            $aluno1->nome,
            $aluno1->instituicao->nome,
            $stats1,
            $aluno2->nome,
            $aluno2->instituicao->nome,
            $stats2
        );

        // Quebra em linhas, agrupa em blocos e exibe até 10 cards
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

    private function gerarNarracao(
        string $n1,
        string $i1,
        array  $s1,
        string $n2,
        string $i2,
        array  $s2
    ): array {
        // helper para envolver nomes em <strong>
        $wrapBold = fn(string $nome): string => "<strong>{$nome}</strong>";

        $randFloat = fn() => mt_rand() / mt_getrandmax();
        $tpl       = config('comparativo.templates');
        $maxE      = config('comparativo.eventos');

        // placar temporário (só cestas da simulação)
        $placar   = [$n1 => 0, $n2 => 0];
        $narracao = ["🏀 Início: {$wrapBold($n1)} ({$i1}) vs {$wrapBold($n2)} ({$i2})"];

        // helper de intensidade
        $pick = function (array $arr, int $e) use ($maxE) {
            $terco = intdiv($maxE, 3);
            if ($e < $terco) {
                return $arr[array_rand(array_slice($arr, 0, 2))];
            } elseif ($e < 2 * $terco) {
                return $arr[array_rand(array_slice($arr, 2, 2))];
            } else {
                return $arr[array_rand(array_slice($arr, 4))];
            }
        };

        // loop de eventos reais
        for ($e = 0; $e < $maxE; $e++) {
            if ($e === 0) {
                $narracao[] = "🎤 Torcida se acomoda...";
            } elseif ($e === intdiv($maxE, 3)) {
                $narracao[] = "📈 Jogo esquenta!";
            } elseif ($e === 2 * intdiv($maxE, 3)) {
                $narracao[] = "🔥 Clima de decisão!";
            }

            // atacante e defensor
            if ($e % 2 === 0) {
                [$at, $ast, $df, $dfst] = [$n1, $s1, $n2, $s2];
            } else {
                [$at, $ast, $df, $dfst] = [$n2, $s2, $n1, $s1];
            }

            // chance de manter a bola
            $pKeep = ($ast['dominio'] + $ast['jogada'])
                / max(1, $ast['dominio'] + $ast['jogada'] + $dfst['marcacao']);

            if ($randFloat() > $pKeep) {
                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$wrapBold($at), $wrapBold($df)],
                    $pick($tpl['dribles'], $e)
                ) . ", mas {$wrapBold($df)} rouba.";
                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$wrapBold($at), $wrapBold($df)],
                    $pick($tpl['rebotes'], $e)
                );
                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$wrapBold($at), $wrapBold($df)],
                    $pick($tpl['transicoes'], $e)
                );
                continue;
            }

            $narracao[] = str_replace(
                ['{at}', '{df}'],
                [$wrapBold($at), $wrapBold($df)],
                $pick($tpl['dribles'], $e)
            ) . " e vai ao ataque.";

            $finishTpl = $pick($tpl['finishes'], $e);
            // escala finalização 0–10 → 0%–100%
            if ($randFloat() <= $ast['finalizacao'] / 10) {
                $placar[$at] += 2;
                $narracao[] = str_replace(
                    '{at}',
                    $wrapBold($at),
                    $finishTpl
                ) . " — *cesta!* +2 pontos.";
            } else {
                $narracao[] = $pick($tpl['misses'], $e) . ".";
            }
        }

        // soma dos 4 atributos
        $sum1 = $s1['arremesso']   + $s1['marcacao']
            + $s1['finalizacao'] + $s1['dominio'];
        $sum2 = $s2['arremesso']   + $s2['marcacao']
            + $s2['finalizacao'] + $s2['dominio'];
        $diff = $sum1 - $sum2;

        // vencedor técnico e número de cestas
        if ($diff > 0) {
            $winner = $n1;
            $loser  = $n2;
            $cW     = $s1['arremesso'] + 1;              // cestas vencedor
            $cL     = (int)ceil($s2['arremesso'] / 2);   // cestas perdedor
            $narracao[] = "📊 {$wrapBold($winner)} vence tecnicamente pelos atributos.";
            $narracao[] = "🏆 Vitória técnica de {$wrapBold($winner)}!";

            // resumo de cestas
            $narracao[] = "🏀 {$wrapBold($winner)} converteu {$cW} "
                . ($cW === 1 ? "cesta" : "cestas")
                . " no total!";
            if ($cL > 0) {
                $narracao[] = "🏀 {$wrapBold($loser)} converteu {$cL} "
                    . ($cL === 1 ? "cesta de honra" : "cestas de honra")
                    . "!";
            }

            $placar[$winner] = 2 * $cW;
            $placar[$loser]  = 2 * $cL;
        } elseif ($diff < 0) {
            $winner = $n2;
            $loser  = $n1;
            $cW     = $s2['arremesso'] + 1;
            $cL     = (int)ceil($s1['arremesso'] / 2);
            $narracao[] = "📊 {$wrapBold($winner)} vence tecnicamente pelos atributos.";
            $narracao[] = "🏆 Vitória técnica de {$wrapBold($winner)}!";

            $narracao[] = "🏀 {$wrapBold($winner)} converteu {$cW} "
                . ($cW === 1 ? "cesta" : "cestas")
                . " no total!";
            if ($cL > 0) {
                $narracao[] = "🏀 {$wrapBold($loser)} converteu {$cL} "
                    . ($cL === 1 ? "cesta de honra" : "cestas de honra")
                    . "!";
            }

            $placar[$winner] = 2 * $cW;
            $placar[$loser]  = 2 * $cL;
        } else {
            $narracao[] = "📊 Empate técnico puro!";
            $placar      = [$n1 => 0, $n2 => 0];
        }

        // placar final
        $narracao[] = "⏱️ Placar final: {$wrapBold($n1)} {$placar[$n1]} x {$placar[$n2]} {$wrapBold($n2)}";

        return [
            'narracao' => implode("\n\n", $narracao),
            'placar'   => $placar,
        ];
    }
}
