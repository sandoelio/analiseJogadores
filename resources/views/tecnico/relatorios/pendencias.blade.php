@extends('layouts.app')

@section('title', 'Pendencias da Instituicao')

@push('styles')
    <style>
        .pendencias-shell {
            max-width: 1180px;
            width: 100%;
            margin: 0 auto;
            padding: 0.9rem 0 2rem;
        }

        @media (min-width: 992px) {
            .pendencias-shell {
                padding-top: 1.2rem;
            }
        }

        .pendencias-topo {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 220px;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .pendencias-heading,
        .pendencias-acoes,
        .pendencias-resumo,
        .pendencia-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .pendencias-heading {
            flex: 1 1 auto;
            padding: 1rem 1.1rem;
        }

        .pendencias-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.55rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .pendencias-titulo {
            margin: 0;
            color: #1e2b4f;
            font-size: 1.45rem;
            font-weight: 700;
        }

        .pendencias-subtitulo {
            margin: 0.35rem 0 0;
            color: #000;
            font-weight: 600;
        }

        .pendencias-texto {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .pendencias-acoes {
            padding: 0.9rem;
            display: grid;
            gap: 0.75rem;
            align-content: start;
        }

        .pendencias-btn {
            width: 100%;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .pendencias-btn-principal {
            background: #28365F;
            border: 1px solid #28365F;
            color: #fff;
            text-decoration: none;
        }

        .pendencias-btn-principal:hover {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .pendencias-acoes-texto {
            margin: 0;
            color: #5f6b85;
            font-size: 0.84rem;
            line-height: 1.45;
        }

        .pendencias-resumo {
            padding: 0.95rem 1rem;
            margin-bottom: 1rem;
        }

        .pendencias-resumo-cards {
            padding: 0.95rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .pendencias-resumo-cards-mobile {
            display: none;
        }

        .pendencias-resumo-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .pendencias-resumo-card {
            padding: 0.9rem;
            border: 1px solid #f0c29a;
            border-radius: 0.95rem;
            background: linear-gradient(135deg, #fff8f1 0%, #ffffff 100%);
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .pendencias-resumo-topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.65rem;
        }

        .pendencias-resumo-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            background: #eef3fb;
            color: #28365F;
            font-size: 1.15rem;
        }

        .pendencias-resumo-numero {
            color: #1f2d4f;
            font-size: 1.55rem;
            font-weight: 700;
            line-height: 1;
            text-align: right;
        }

        .pendencias-resumo-titulo {
            margin: 0;
            color: #1f2d4f;
            font-size: 0.96rem;
            font-weight: 700;
        }

        .pendencias-resumo-texto {
            margin: 0.2rem 0 0;
            color: #5f6b85;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        .pendencias-grid {
            display: grid;
            gap: 1rem;
        }

        .pendencia-card {
            overflow: hidden;
        }

        .pendencia-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #edf2f8;
        }

        .pendencia-card-titulo {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.02rem;
            font-weight: 700;
        }

        .pendencia-card-texto {
            margin: 0.25rem 0 0;
            color: #5f6b85;
            font-size: 0.86rem;
            line-height: 1.45;
        }

        .pendencia-card-total {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 96px;
            min-height: 38px;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            background: #fff7ef;
            border: 1px solid #f3c299;
            color: #9a4e16;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .pendencia-card-body {
            padding: 1rem 1.1rem 1.1rem;
        }

        .pendencia-vazio {
            margin: 0;
            padding: 0.85rem 0.95rem;
            border-radius: 0.9rem;
            background: #eef9f1;
            color: #2f7a44;
            font-weight: 700;
        }

        .pendencias-vazio-geral {
            margin: 0;
            padding: 1rem 1.05rem;
            border-radius: 0.95rem;
            background: #eef9f1;
            color: #2f7a44;
            font-weight: 700;
        }

        .pendencia-tabela-wrap {
            width: 100%;
            max-height: 300px;
            overflow: auto;
            border: 1px solid #d8dee9;
            border-radius: 0.85rem;
        }

        .pendencia-tabela {
            width: 100%;
            min-width: 760px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .pendencia-tabela th,
        .pendencia-tabela td {
            padding: 0.78rem 0.85rem;
            border-bottom: 1px solid #d8dee9;
            border-right: 1px solid #d8dee9;
            text-align: left;
            vertical-align: top;
        }

        .pendencia-tabela th:last-child,
        .pendencia-tabela td:last-child {
            border-right: none;
        }

        .pendencia-tabela th {
            position: sticky;
            top: 0;
            background: #223154;
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
            z-index: 1;
        }

        .pendencia-tabela td {
            background: #fff;
            color: #263248;
            font-size: 0.88rem;
        }

        .pendencia-tabela td:last-child {
            min-width: 220px;
        }

        .pendencia-meta {
            color: #5f6b85;
            font-size: 0.8rem;
        }

        .pendencia-mobile-lista {
            display: none;
        }

        .pendencia-mobile-wrap {
            max-height: 420px;
            overflow: auto;
            display: grid;
            gap: 0.65rem;
            padding-right: 0.2rem;
        }

        .pendencia-mobile-card {
            border: 1px solid #e5eaf3;
            border-radius: 0.95rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 0.85rem 0.9rem;
        }

        .pendencia-mobile-topo {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.55rem;
        }

        .pendencia-mobile-nome {
            margin: 0;
            color: #1f2d4f;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .pendencia-mobile-meta {
            margin: 0.18rem 0 0;
            color: #5f6b85;
            font-size: 0.78rem;
        }

        .pendencia-mobile-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #31476f;
            font-size: 0.74rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .pendencia-mobile-observacao {
            margin: 0;
            color: #263248;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        @media (max-width: 767.98px) {
            .pendencias-topo {
                grid-template-columns: 1fr;
            }

            .pendencias-titulo {
                font-size: 1.16rem;
            }

            .pendencias-resumo-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.7rem;
            }

            .pendencia-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.55rem;
            }
        }

        @media (max-width: 575.98px) {
            .pendencias-shell {
                padding-top: 0.55rem;
            }

            .pendencias-heading,
            .pendencias-acoes,
            .pendencias-resumo,
            .pendencias-resumo-cards,
            .pendencia-card {
                border-radius: 1rem;
            }

            .pendencias-heading,
            .pendencias-acoes,
            .pendencias-resumo,
            .pendencias-resumo-cards,
            .pendencia-card-body {
                padding-left: 0.9rem;
                padding-right: 0.9rem;
            }

            .pendencias-texto,
            .pendencias-acoes-texto {
                font-size: 0.82rem;
            }

            .pendencias-acoes {
                padding: 0.8rem 0.9rem;
                gap: 0.5rem;
            }

            .pendencias-btn {
                min-height: 38px;
                font-size: 0.92rem;
                border-radius: 0.8rem;
            }

            .pendencias-resumo-grid {
                grid-template-columns: 1fr;
            }

            .pendencias-resumo-card {
                padding: 0.8rem;
            }

            .pendencias-resumo-cards-desktop {
                display: none;
            }

            .pendencias-resumo-cards-mobile {
                display: block;
            }

            .pendencias-resumo-numero {
                font-size: 1.35rem;
            }

            .pendencia-tabela-wrap {
                display: none;
            }

            .pendencia-mobile-lista {
                display: grid;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $pendenciasResumo = $pendencias;
        $pendenciasDetalhe = $pendencias->filter(fn ($pendencia) => (int) $pendencia['total'] > 0)->values();
        $pendenciasResumoMobile = $pendenciasDetalhe;
    @endphp

    <div class="pendencias-shell">
        <div class="pendencias-topo">
            <div class="pendencias-heading">
                <span class="pendencias-chip">
                    <i class="bi bi-exclamation-diamond"></i>
                    Pendencias
                </span>
                <p class="pendencias-subtitulo">
                    Instituicao: {{ $instituicao->nome ?? 'Nao informada' }}
                </p>
                <p class="pendencias-texto">
                    Consulte os pontos que exigem acao mais rapida no cadastro e nas ultimas analises dos atletas.
                </p>
            </div>

            <div class="pendencias-acoes">
                <a href="{{ route('tecnico.relatorios') }}" class="pendencias-btn pendencias-btn-principal">
                    <i></i>
                    Voltar para relatorios
                </a>
                <p class="pendencias-acoes-texto">
                    O total abaixo soma todos os apontamentos encontrados nos atletas da sua instituicao.
                </p>
            </div>
        </div>

        @if ($pendenciasResumo->isNotEmpty())
            <div class="pendencias-resumo-cards pendencias-resumo-cards-desktop">
                <div class="pendencias-resumo-grid">
                    @foreach ($pendenciasResumo as $pendencia)
                        <div class="pendencias-resumo-card">
                            <div class="pendencias-resumo-topo">
                                <span class="pendencias-resumo-icon">
                                    <i class="bi {{ $pendencia['icone'] }}"></i>
                                </span>
                                <div class="pendencias-resumo-numero">{{ $pendencia['total'] }}</div>
                            </div>
                            <h2 class="pendencias-resumo-titulo">{{ $pendencia['titulo'] }}</h2>
                            <p class="pendencias-resumo-texto">{{ $pendencia['descricao'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($pendenciasResumoMobile->isNotEmpty())
            <div class="pendencias-resumo-cards pendencias-resumo-cards-mobile">
                <div class="pendencias-resumo-grid">
                    @foreach ($pendenciasResumoMobile as $pendencia)
                        <div class="pendencias-resumo-card">
                            <div class="pendencias-resumo-topo">
                                <span class="pendencias-resumo-icon">
                                    <i class="bi {{ $pendencia['icone'] }}"></i>
                                </span>
                                <div class="pendencias-resumo-numero">{{ $pendencia['total'] }}</div>
                            </div>
                            <h2 class="pendencias-resumo-titulo">{{ $pendencia['titulo'] }}</h2>
                            <p class="pendencias-resumo-texto">{{ $pendencia['descricao'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="pendencias-resumo mb-3">
            <strong>Total geral:</strong> {{ $totalPendencias }} apontamentos
        </div>

        @if ($pendenciasDetalhe->isEmpty())
            <p class="pendencias-vazio-geral">Nao ha pendencias ativas para detalhar no momento.</p>
        @else
        <div class="pendencias-grid">
            @foreach ($pendenciasDetalhe as $pendencia)
                <section class="pendencia-card">
                    <div class="pendencia-card-header">
                        <div>
                            <h2 class="pendencia-card-titulo">{{ $pendencia['titulo'] }}</h2>
                            <p class="pendencia-card-texto">{{ $pendencia['descricao'] }}</p>
                        </div>

                        <span class="pendencia-card-total">{{ $pendencia['total'] }} atletas</span>
                    </div>

                    <div class="pendencia-card-body">
                        @if ($pendencia['itens']->isEmpty())
                            <p class="pendencia-vazio">Nenhuma pendencia encontrada nesse item.</p>
                        @else
                            <div class="pendencia-tabela-wrap">
                                <table class="pendencia-tabela">
                                    <thead>
                                        <tr>
                                            <th>Atleta</th>
                                            <th>Idade</th>
                                            <th>Sexo</th>
                                            <th>{{ $pendencia['coluna_data'] }}</th>
                                            <th>Observacao</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendencia['itens'] as $item)
                                            <tr>
                                                <td>{{ $item['nome'] }}</td>
                                                <td>{{ $item['idade'] ?? '--' }}</td>
                                                <td>{{ $item['sexo'] ?? '--' }}</td>
                                                <td>{{ optional($item['data_referencia'])->format('d/m/Y') ?? '--' }}</td>
                                                <td>{{ $item['observacao'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="pendencia-mobile-lista">
                                <div class="pendencia-mobile-wrap">
                                    @foreach ($pendencia['itens'] as $item)
                                        <div class="pendencia-mobile-card">
                                            <div class="pendencia-mobile-topo">
                                                <div>
                                                    <h3 class="pendencia-mobile-nome">{{ $item['nome'] }}</h3>
                                                    <p class="pendencia-mobile-meta">
                                                        Idade: {{ $item['idade'] ?? '--' }} | Sexo: {{ $item['sexo'] ?? '--' }}
                                                    </p>
                                                </div>

                                                <span class="pendencia-mobile-badge">
                                                    {{ optional($item['data_referencia'])->format('d/m/Y') ?? '--' }}
                                                </span>
                                            </div>

                                            <p class="pendencia-mobile-observacao">{{ $item['observacao'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            @endforeach
        </div>
        @endif
    </div>
@endsection
