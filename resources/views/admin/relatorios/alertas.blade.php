@extends('layouts.app')

@section('title', 'Alertas Administrativos')

@push('styles')
    <style>
        .alertas-shell { max-width: 1220px; width: 100%; margin: 0 auto; padding-bottom: 2rem; }
        @media (min-width: 992px) { .alertas-shell { padding-top: 1.2rem; } }
        .alertas-topo, .alertas-card, .alertas-resumo-wrap { border: 1px solid #dbe1ec; border-radius: 0.9rem; background: #fff; box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06); }
        .alertas-topo { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 0.95rem 1rem; margin-bottom: 1rem; }
        .alertas-chip { display: inline-flex; align-items: center; gap: 0.45rem; margin-bottom: 0.55rem; padding: 0.32rem 0.7rem; border-radius: 999px; background: #eef3fb; color: #28365F; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; }
        .alertas-title { margin: 0; color: #1f2d4f; font-size: 1.3rem; font-weight: 700; }
        .alertas-texto { margin: 0.35rem 0 0; color: #5f6b85; font-size: 0.88rem; line-height: 1.45; max-width: 780px; }
        .alertas-resumo-wrap { padding: 0.9rem; margin-bottom: 1rem; }
        .alertas-resumo-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.8rem; }
        .alertas-resumo-card { border: 1px solid #e5eaf3; border-radius: 0.9rem; background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); padding: 0.9rem; }
        .alertas-resumo-card h3 { margin: 0; color: #1f2d4f; font-size: 0.95rem; font-weight: 700; }
        .alertas-resumo-card p { margin: 0.35rem 0 0; color: #5f6b85; font-size: 0.82rem; line-height: 1.4; }
        .alertas-numero { color: #1f2d4f; font-size: 1.55rem; font-weight: 700; line-height: 1; margin-top: 0.7rem; }
        .alertas-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.9rem; }
        .alertas-card { padding: 0.95rem; }
        .alertas-card h2 { margin: 0; color: #1f2d4f; font-size: 1rem; font-weight: 700; }
        .alertas-card p { margin: 0.35rem 0 0.85rem; color: #5f6b85; font-size: 0.84rem; line-height: 1.4; }
        .alertas-tabela-wrap { max-height: 345px; overflow: auto; border: 1px solid #e5eaf3; border-radius: 0.85rem; }
        .alertas-tabela { width: 100%; min-width: 620px; border-collapse: separate; border-spacing: 0; }
        .alertas-tabela th, .alertas-tabela td { padding: 0.7rem 0.8rem; border-bottom: 1px solid #dbe1ec; vertical-align: top; }
        .alertas-tabela th { position: sticky; top: 0; background: #223154; color: #fff; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; white-space: nowrap; }
        .alertas-tabela td { background: #fff; color: #263248; font-size: 0.84rem; }
        .alertas-tabela tbody tr:last-child td { border-bottom: none; }
        .alertas-mobile-lista { display: none; }
        .alertas-mobile-item { border: 1px solid #e5eaf3; border-radius: 0.85rem; background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); padding: 0.8rem; }
        .alertas-mobile-item + .alertas-mobile-item { margin-top: 0.65rem; }
        .alertas-mobile-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.65rem; margin-bottom: 0.45rem; }
        .alertas-mobile-head strong { color: #1f2d4f; font-size: 0.92rem; }
        .alertas-mobile-badge { display: inline-flex; align-items: center; justify-content: center; min-height: 28px; padding: 0.22rem 0.6rem; border-radius: 999px; background: #eef3fb; color: #28365F; font-size: 0.75rem; font-weight: 700; }
        .alertas-mobile-linha { display: grid; grid-template-columns: 88px 1fr; gap: 0.5rem; font-size: 0.8rem; line-height: 1.35; padding-top: 0.35rem; }
        .alertas-mobile-rotulo { color: #5f6b85; font-weight: 700; }
        .alertas-mobile-valor { color: #263248; }
        .alertas-vazio { margin: 0; padding: 0.9rem; border-radius: 0.85rem; background: #f7f9fc; color: #5f6b85; font-weight: 600; }
        @media (max-width: 991.98px) { .alertas-resumo-grid, .alertas-grid { grid-template-columns: 1fr; } }
        @media (max-width: 767.98px) { .alertas-topo { flex-direction: column; } .alertas-topo .btn { width: 100%; } }
        @media (max-width: 575.98px) {
            .alertas-shell { padding-top: 0.45rem; }
            .alertas-resumo-wrap, .alertas-card, .alertas-topo { padding: 0.8rem; }
            .alertas-title { font-size: 1.12rem; }
            .alertas-texto { font-size: 0.82rem; line-height: 1.35; }
            .alertas-resumo-grid { grid-template-columns: 1fr; gap: 0.6rem; }
            .alertas-resumo-card { padding: 0.75rem; }
            .alertas-resumo-card h3 { font-size: 0.9rem; }
            .alertas-resumo-card p { font-size: 0.76rem; line-height: 1.32; }
            .alertas-numero { margin-top: 0.45rem; font-size: 1.2rem; }
            .alertas-card p { font-size: 0.78rem; margin-bottom: 0.7rem; }
            .alertas-tabela-wrap { display: none; }
            .alertas-mobile-lista { display: block; }
        }
    </style>
@endpush

@section('content')
    <div class="alertas-shell">
        <div class="alertas-topo">
            <div>
                <span class="alertas-chip"><i class="bi bi-bell"></i> Alertas</span>
                <h1 class="alertas-title">Alertas administrativos</h1>
                <p class="alertas-texto">
                    Painel gerencial com instituicoes que pedem atencao por baixa presenca feminina, concentracao etaria excessiva ou baixa atualizacao de analises.
                </p>
            </div>

            <a href="{{ route('admin.relatorios') }}" class="btn btn-secondary">Voltar</a>
        </div>

        @php
            $cardsResumo = [
                'baixa_presenca_feminina' => [
                    'titulo' => 'Baixa presenca feminina',
                    'descricao' => 'Instituicoes com participacao feminina abaixo do limite de atencao.',
                ],
                'concentracao_etaria' => [
                    'titulo' => 'Concentracao etaria',
                    'descricao' => 'Instituicoes com forte concentracao de atletas em uma idade dominante.',
                ],
                'baixa_atualizacao' => [
                    'titulo' => 'Baixa atualizacao',
                    'descricao' => 'Instituicoes com volume alto de atletas sem analise recente.',
                ],
            ];
        @endphp

        <div class="alertas-resumo-wrap d-none d-sm-block">
            <div class="alertas-resumo-grid">
                @foreach ($cardsResumo as $chave => $card)
                    <div class="alertas-resumo-card">
                        <h3>{{ $card['titulo'] }}</h3>
                        <p>{{ $card['descricao'] }}</p>
                        <div class="alertas-numero">{{ $resumoAlertas[$chave] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="alertas-resumo-wrap d-block d-sm-none">
            <div class="alertas-resumo-grid">
                @foreach ($cardsResumo as $chave => $card)
                    @if ($resumoAlertas[$chave] > 0)
                        <div class="alertas-resumo-card">
                            <h3>{{ $card['titulo'] }}</h3>
                            <p>{{ $card['descricao'] }}</p>
                            <div class="alertas-numero">{{ $resumoAlertas[$chave] }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="alertas-grid">
            @foreach ([
                'baixa_presenca_feminina' => 'Baixa presenca feminina',
                'concentracao_etaria' => 'Concentracao etaria',
                'baixa_atualizacao' => 'Baixa atualizacao de analises',
            ] as $chave => $titulo)
                <div class="alertas-card">
                    <h2>{{ $titulo }}</h2>
                    <p>Mostra apenas instituicoes que realmente estao dentro do criterio de alerta.</p>

                    @if ($alertas[$chave]->isEmpty())
                        <p class="alertas-vazio">Nenhuma instituicao enquadrada neste alerta no momento.</p>
                    @else
                        <div class="alertas-tabela-wrap">
                            <table class="alertas-tabela">
                                <thead>
                                    <tr>
                                        <th>Instituicao</th>
                                        <th>Total</th>
                                        <th>Indicador</th>
                                        <th>Percentual</th>
                                        <th>Observacao</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alertas[$chave] as $item)
                                        <tr>
                                            <td>{{ $item['instituicao'] }}</td>
                                            <td>{{ $item['total_atletas'] }}</td>
                                            <td>{{ $item['indicador'] }}</td>
                                            <td>{{ $item['percentual'] }}</td>
                                            <td>{{ $item['observacao'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="alertas-mobile-lista">
                            @foreach ($alertas[$chave] as $item)
                                <div class="alertas-mobile-item">
                                    <div class="alertas-mobile-head">
                                        <strong>{{ $item['instituicao'] }}</strong>
                                        <span class="alertas-mobile-badge">{{ $item['percentual'] }}</span>
                                    </div>

                                    <div class="alertas-mobile-linha">
                                        <span class="alertas-mobile-rotulo">Total</span>
                                        <span class="alertas-mobile-valor">{{ $item['total_atletas'] }}</span>
                                    </div>

                                    <div class="alertas-mobile-linha">
                                        <span class="alertas-mobile-rotulo">Indicador</span>
                                        <span class="alertas-mobile-valor">{{ $item['indicador'] }}</span>
                                    </div>

                                    <div class="alertas-mobile-linha">
                                        <span class="alertas-mobile-rotulo">Observacao</span>
                                        <span class="alertas-mobile-valor">{{ $item['observacao'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
