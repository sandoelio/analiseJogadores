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
        $wrapBold = fn(string $nome): string => "<strong>{$nome}</strong>";
        $randFloat = fn() => mt_rand() / mt_getrandmax();

        // carrega todos os templates, inclusive 'converts'
        $tpl  = config('comparativo.templates');
        $maxE = config('comparativo.eventos');

        // placar “real” durante o loop
        $placar   = [$n1 => 0, $n2 => 0];
        $narracao = ["🏀 Início: {$wrapBold($n1)} ({$i1}) vs {$wrapBold($n2)} ({$i2})"];

        // helper para escolher frase por intensidade do evento
        $pick = function (array $arr, int $e) use ($maxE) {
            $terco = intdiv($maxE, 3);
            if ($e < $terco) {
                $slice = array_slice($arr, 0, 2);
            } elseif ($e < 2 * $terco) {
                $slice = array_slice($arr, 2, 2);
            } else {
                $slice = array_slice($arr, 4);
            }
            return $slice[array_rand($slice)];
        };

        // 1) Loop de eventos
        for ($e = 0; $e < $maxE; $e++) {
            if ($e === 0) {
                $narracao[] = "🎤 Torcida se acomoda...";
            } elseif ($e === intdiv($maxE, 3)) {
                $narracao[] = "📈 Jogo esquenta!";
            } elseif ($e === 2 * intdiv($maxE, 3)) {
                $narracao[] = "🔥 Clima de decisão!";
            }

            // atacante/defensor
            if ($e % 2 === 0) {
                [$at, $ast, $df, $dfst] = [$n1, $s1, $n2, $s2];
            } else {
                [$at, $ast, $df, $dfst] = [$n2, $s2, $n1, $s1];
            }

            // chance de manter posse
            $pKeep = ($ast['dominio'] + $ast['rebote'])
                / max(1, $ast['dominio'] + $ast['rebote'] + $dfst['marcacao']);

            if ($randFloat() > $pKeep) {
                // drible falhou
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

            // drible bem-sucedido
            $narracao[] = str_replace(
                ['{at}', '{df}'],
                [$wrapBold($at), $wrapBold($df)],
                $pick($tpl['dribles'], $e)
            ) . " e vai ao ataque.";

            // 2) arremesso → ou converte com 'converts', ou erra com 'misses'
            if ($randFloat() <= $ast['bandeja'] / 10) {
                // acerto: pega frase de converts
                $placar[$at] += 2;
                $convertTpl = $pick($tpl['converts'], $e);
                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$wrapBold($at), $wrapBold($df)],
                    $convertTpl
                ) . " — +2 pontos.";
            } else {
                // erro
                $narracao[] = str_replace(
                    ['{at}', '{df}'],
                    [$wrapBold($at), $wrapBold($df)],
                    $pick($tpl['misses'], $e)
                ) . ".";
            }
        }

        // 3) decisão técnica
        $sum1 = $s1['arremesso']   + $s1['marcacao']
            + $s1['bandeja'] + $s1['dominio'];
        $sum2 = $s2['arremesso']   + $s2['marcacao']
            + $s2['bandeja'] + $s2['dominio'];
        $diff = $sum1 - $sum2;

        if ($diff > 0) {
            $winner = $n1;
            $loser = $n2;
            $cW     = $s1['arremesso'] + 1;
            $cL     = (int)ceil($s2['arremesso'] / 2);
            $narracao[] = "📊 {$wrapBold($winner)} vence tecnicamente pelos atributos.";
            $narracao[] = "🏆 Vitória técnica de {$wrapBold($winner)}!";
            $narracao[] = "🏀 {$wrapBold($winner)} converteu {$cW} "
                . ($cW === 1 ? 'cesta' : 'cestas') . " no total!";
            if ($cL) {
                $narracao[] = "🏀 {$wrapBold($loser)} converteu {$cL} "
                    . ($cL === 1 ? 'cesta de honra' : 'cestas de honra') . "!";
            }
            $placar[$winner] = 2 * $cW;
            $placar[$loser]  = 2 * $cL;
        } elseif ($diff < 0) {
            $winner = $n2;
            $loser = $n1;
            $cW     = $s2['arremesso'] + 1;
            $cL     = (int)ceil($s1['arremesso'] / 2);
            $narracao[] = "📊 {$wrapBold($winner)} vence tecnicamente pelos atributos.";
            $narracao[] = "🏆 Vitória técnica de {$wrapBold($winner)}!";
            $narracao[] = "🏀 {$wrapBold($winner)} converteu {$cW} "
                . ($cW === 1 ? 'cesta' : 'cestas') . " no total!";
            if ($cL) {
                $narracao[] = "🏀 {$wrapBold($loser)} converteu {$cL} "
                    . ($cL === 1 ? 'cesta de honra' : 'cestas de honra') . "!";
            }
            $placar[$winner] = 2 * $cW;
            $placar[$loser]  = 2 * $cL;
        } else {
            // empate técnico: ambos convertem (arremesso+1)
            $c1 = $s1['arremesso'] + 1;
            $c2 = $s2['arremesso'] + 1;
            $narracao[] = "📊 Empate técnico! Ambos dominaram os atributos.";
            $narracao[] = "🏀 {$wrapBold($n1)} converteu {$c1} "
                . ($c1 === 1 ? 'cesta' : 'cestas') . " no total!";
            $narracao[] = "🏀 {$wrapBold($n2)} converteu {$c2} "
                . ($c2 === 1 ? 'cesta' : 'cestas') . " no total!";
            $placar[$n1] = 2 * $c1;
            $placar[$n2] = 2 * $c2;
        }

        // 4) linha final
        $narracao[] = "⏱️ Placar final: {$wrapBold($n1)} {$placar[$n1]} x {$placar[$n2]} {$wrapBold($n2)}";

        return [
            'narracao' => implode("\n\n", $narracao),
            'placar'   => $placar,
        ];
    }
}
