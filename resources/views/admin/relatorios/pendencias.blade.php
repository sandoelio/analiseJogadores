@extends('layouts.app')

@section('title', 'Pendencias dos Projetos')

@push('styles')
    <style>
        .admin-pendencias-shell {
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
            padding-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .admin-pendencias-shell {
                padding-top: 1.2rem;
            }
        }

        .admin-pendencias-topo,
        .admin-pendencias-filtro,
        .admin-pendencias-resumo,
        .admin-pendencia-card {
            border: 1px solid #dbe1ec;
            border-radius: 0.9rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
        }

        .admin-pendencias-topo {
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
        }

        .admin-pendencias-chip {
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

        .admin-pendencias-titulo {
            margin: 0;
            color: #1e2b4f;
            font-size: 1.45rem;
            font-weight: 700;
        }

        .admin-pendencias-texto {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .admin-pendencias-filtro {
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
        }

        .admin-pendencias-filtro-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto auto;
            gap: 0.75rem;
            align-items: end;
        }

        .admin-pendencias-label {
            color: #33405f;
            font-size: 0.84rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
            display: block;
        }

        .admin-pendencias-select {
            min-height: 46px;
            border-radius: 0.85rem;
            border-color: #dbe1ec;
        }

        .admin-pendencias-btn {
            min-height: 44px;
            padding: 0.6rem 1rem;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .admin-pendencias-btn-principal {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .admin-pendencias-btn-principal:hover {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .admin-pendencias-resumo {
            padding: 0.95rem 1rem;
            margin-bottom: 1rem;
        }

        .admin-pendencias-resumo-cards {
            padding: 0.95rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid #dbe1ec;
            border-radius: 0.9rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
        }

        .admin-pendencias-resumo-cards-mobile {
            display: none;
        }

        .admin-pendencias-resumo-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .admin-pendencias-resumo-card {
            padding: 0.9rem;
            border: 1px solid #f0c29a;
            border-radius: 0.95rem;
            background: linear-gradient(135deg, #fff8f1 0%, #ffffff 100%);
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .admin-pendencias-resumo-topo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.65rem;
        }

        .admin-pendencias-resumo-icon {
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

        .admin-pendencias-resumo-numero {
            color: #1f2d4f;
            font-size: 1.55rem;
            font-weight: 700;
            line-height: 1;
            text-align: right;
        }

        .admin-pendencias-resumo-titulo {
            margin: 0;
            color: #1f2d4f;
            font-size: 0.96rem;
            font-weight: 700;
        }

        .admin-pendencias-resumo-texto {
            margin: 0.2rem 0 0;
            color: #5f6b85;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        .admin-pendencias-grid {
            display: grid;
            gap: 1rem;
        }

        .admin-pendencia-card {
            overflow: hidden;
        }

        .admin-pendencia-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #edf2f8;
        }

        .admin-pendencia-card-titulo {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.02rem;
            font-weight: 700;
        }

        .admin-pendencia-card-texto {
            margin: 0.25rem 0 0;
            color: #5f6b85;
            font-size: 0.86rem;
            line-height: 1.45;
        }

        .admin-pendencia-card-total {
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

        .admin-pendencia-card-body {
            padding: 1rem 1.1rem 1.1rem;
        }

        .admin-pendencia-vazio {
            margin: 0;
            padding: 0.85rem 0.95rem;
            border-radius: 0.9rem;
            background: #f7f9fc;
            color: #5f6b85;
            font-weight: 600;
        }

        .admin-pendencias-vazio-geral {
            margin: 0;
            padding: 1rem 1.05rem;
            border-radius: 0.95rem;
            background: #eef9f1;
            color: #2f7a44;
            font-weight: 700;
        }

        .admin-pendencia-tabela-wrap {
            max-height: 300px;
            overflow: auto;
            border: 1px solid #d8dee9;
            border-radius: 0.85rem;
        }

        .admin-pendencia-tabela {
            width: 100%;
            min-width: 760px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .admin-pendencia-tabela th,
        .admin-pendencia-tabela td {
            padding: 0.78rem 0.85rem;
            border-bottom: 1px solid #d8dee9;
            border-right: 1px solid #d8dee9;
            text-align: left;
            vertical-align: top;
        }

        .admin-pendencia-tabela th:last-child,
        .admin-pendencia-tabela td:last-child {
            border-right: none;
        }

        .admin-pendencia-tabela th {
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

        .admin-pendencia-tabela td {
            background: #fff;
            color: #263248;
            font-size: 0.88rem;
        }

        .admin-pendencia-mobile-lista {
            display: none;
        }

        .admin-pendencia-mobile-wrap {
            max-height: 420px;
            overflow: auto;
            display: grid;
            gap: 0.65rem;
            padding-right: 0.2rem;
        }

        .admin-pendencia-mobile-card {
            border: 1px solid #e5eaf3;
            border-radius: 0.95rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 0.85rem 0.9rem;
        }

        .admin-pendencia-mobile-topo {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.55rem;
        }

        .admin-pendencia-mobile-nome {
            margin: 0;
            color: #1f2d4f;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .admin-pendencia-mobile-meta {
            margin: 0.18rem 0 0;
            color: #5f6b85;
            font-size: 0.78rem;
        }

        .admin-pendencia-mobile-badge {
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

        .admin-pendencia-mobile-observacao {
            margin: 0;
            color: #263248;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        @media (max-width: 767.98px) {
            .admin-pendencias-filtro-grid {
                grid-template-columns: 1fr;
            }

            .admin-pendencias-resumo-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.7rem;
            }

            .admin-pendencia-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.55rem;
            }
        }

        @media (max-width: 575.98px) {
            .admin-pendencias-shell {
                padding-top: 0.35rem;
            }

            .admin-pendencias-topo,
            .admin-pendencias-filtro,
            .admin-pendencias-resumo,
            .admin-pendencias-resumo-cards,
            .admin-pendencia-card {
                border-radius: 1rem;
            }

            .admin-pendencias-resumo-grid {
                grid-template-columns: 1fr;
            }

            .admin-pendencias-resumo-cards-desktop {
                display: none;
            }

            .admin-pendencias-resumo-cards-mobile {
                display: block;
            }

            .admin-pendencia-tabela-wrap {
                display: none;
            }

            .admin-pendencia-mobile-lista {
                display: block;
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

    <div class="admin-pendencias-shell">
        <div class="admin-pendencias-topo">
            <span class="admin-pendencias-chip">
                <i class="bi bi-shield-exclamation"></i>
                Painel Administrativo
            </span>
            <h1 class="admin-pendencias-titulo">Pendencias por instituicao</h1>
            <p class="admin-pendencias-texto">
                Selecione uma instituicao para abrir o painel com scroll interno e consultar as pendencias sem expandir a pagina inteira.
            </p>
        </div>

        <div class="admin-pendencias-filtro">
            <form method="GET" action="{{ route('admin.relatorios.pendencias') }}">
                <div class="admin-pendencias-filtro-grid">
                    <div>
                        <label for="instituicao_id" class="admin-pendencias-label">Instituicao</label>
                        <select id="instituicao_id" name="instituicao_id" class="form-select admin-pendencias-select" required>
                            <option value="">Selecione a instituicao</option>
                            @foreach ($instituicoes as $instituicao)
                                <option value="{{ $instituicao->id }}" @selected((int) request('instituicao_id') === (int) $instituicao->id)>
                                    {{ $instituicao->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn admin-pendencias-btn admin-pendencias-btn-principal">
                        Abrir painel
                    </button>

                    <a href="{{ route('admin.relatorios') }}" class="btn btn-secondary admin-pendencias-btn">
                        Voltar
                    </a>
                </div>
            </form>
        </div>

        @if ($instituicaoSelecionada)
            @if ($pendenciasResumo->isNotEmpty())
                <div class="admin-pendencias-resumo">
                    <strong>Instituicao selecionada:</strong> {{ $instituicaoSelecionada->nome }}
                    <br>
                    <strong>Total geral:</strong> {{ $totalPendencias }} apontamentos
                </div>

                <div class="admin-pendencias-resumo-cards admin-pendencias-resumo-cards-desktop">
                    <div class="admin-pendencias-resumo-grid">
                        @foreach ($pendenciasResumo as $pendencia)
                            <div class="admin-pendencias-resumo-card">
                                <div class="admin-pendencias-resumo-topo">
                                    <span class="admin-pendencias-resumo-icon">
                                        <i class="bi {{ $pendencia['icone'] }}"></i>
                                    </span>
                                    <div class="admin-pendencias-resumo-numero">{{ $pendencia['total'] }}</div>
                                </div>
                                <h2 class="admin-pendencias-resumo-titulo">{{ $pendencia['titulo'] }}</h2>
                                <p class="admin-pendencias-resumo-texto">{{ $pendencia['descricao'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($pendenciasResumoMobile->isNotEmpty())
                    <div class="admin-pendencias-resumo-cards admin-pendencias-resumo-cards-mobile">
                        <div class="admin-pendencias-resumo-grid">
                            @foreach ($pendenciasResumoMobile as $pendencia)
                                <div class="admin-pendencias-resumo-card">
                                    <div class="admin-pendencias-resumo-topo">
                                        <span class="admin-pendencias-resumo-icon">
                                            <i class="bi {{ $pendencia['icone'] }}"></i>
                                        </span>
                                        <div class="admin-pendencias-resumo-numero">{{ $pendencia['total'] }}</div>
                                    </div>
                                    <h2 class="admin-pendencias-resumo-titulo">{{ $pendencia['titulo'] }}</h2>
                                    <p class="admin-pendencias-resumo-texto">{{ $pendencia['descricao'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="admin-pendencias-vazio-geral">
                    Instituicao selecionada: {{ $instituicaoSelecionada->nome }}. Nao ha pendencias ativas no momento.
                </div>
            @endif

            @if ($pendenciasDetalhe->isEmpty())
                <div class="admin-pendencias-vazio-geral">
                    Instituicao selecionada: {{ $instituicaoSelecionada->nome }}. Nao ha pendencias ativas para detalhar.
                </div>
            @else
            <div class="admin-pendencias-grid">
                @foreach ($pendenciasDetalhe as $pendencia)
                    <section class="admin-pendencia-card">
                        <div class="admin-pendencia-card-header">
                            <div>
                                <h2 class="admin-pendencia-card-titulo">{{ $pendencia['titulo'] }}</h2>
                                <p class="admin-pendencia-card-texto">{{ $pendencia['descricao'] }}</p>
                            </div>
                            <span class="admin-pendencia-card-total">{{ $pendencia['total'] }} atletas</span>
                        </div>

                        <div class="admin-pendencia-card-body">
                            @if ($pendencia['itens']->isEmpty())
                                <p class="admin-pendencia-vazio">Nenhuma pendencia encontrada nesse item.</p>
                            @else
                                <div class="admin-pendencia-tabela-wrap">
                                    <table class="admin-pendencia-tabela">
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

                                <div class="admin-pendencia-mobile-lista">
                                    <div class="admin-pendencia-mobile-wrap">
                                        @foreach ($pendencia['itens'] as $item)
                                            <div class="admin-pendencia-mobile-card">
                                                <div class="admin-pendencia-mobile-topo">
                                                    <div>
                                                        <h3 class="admin-pendencia-mobile-nome">{{ $item['nome'] }}</h3>
                                                        <p class="admin-pendencia-mobile-meta">
                                                            Idade: {{ $item['idade'] ?? '--' }} | Sexo: {{ $item['sexo'] ?? '--' }}
                                                        </p>
                                                    </div>

                                                    <span class="admin-pendencia-mobile-badge">
                                                        {{ optional($item['data_referencia'])->format('d/m/Y') ?? '--' }}
                                                    </span>
                                                </div>

                                                <p class="admin-pendencia-mobile-observacao">{{ $item['observacao'] }}</p>
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
        @else
            <div class="admin-pendencias-resumo">
                Escolha uma instituicao para carregar as pendencias.
            </div>
        @endif
    </div>
@endsection
