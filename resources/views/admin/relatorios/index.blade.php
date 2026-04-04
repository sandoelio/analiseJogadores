@extends('layouts.app')

@section('title', 'Relatorios dos Projetos')

@push('styles')
    <style>
        .relatorios-shell {
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
            padding-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .relatorios-shell {
                padding-top: 1.2rem;
            }
        }

        .relatorios-topo {
            display: grid;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .relatorios-topo-acoes {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.9rem 1rem;
            border: 1px solid #dbe1ec;
            border-radius: 0.8rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
        }

        .relatorios-topo-texto {
            margin: 0;
            color: #5f6b85;
            font-size: 0.86rem;
        }

        .relatorios-acoes-botoes {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .relatorios-pendencias-btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.6rem 1rem;
            border-radius: 0.85rem;
            background: #28365F;
            border: 1px solid #28365F;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .relatorios-pendencias-btn:hover {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .relatorios-subtitulo {
            margin: 0.35rem 0 0;
            color: #000;
            font-weight: 600;
        }

        .relatorios-voltar {
            margin-top: 0.5rem;
        }

        .relatorios-card {
            border: 1px solid #dbe1ec;
            border-radius: 0.8rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
            overflow: hidden;
        }

        .relatorios-card .nav-tabs {
            border-bottom: 1px solid #dbe1ec;
            background: #f7f9fc;
            padding: 0.75rem 0.75rem 0;
            gap: 0.4rem;
        }

        .relatorios-card .nav-link {
            border: 1px solid #dbe1ec;
            border-bottom: none;
            color: #2a3b5f;
            font-weight: 600;
            background: #edf2f8;
        }

        .relatorios-card .nav-link.active {
            color: #fff;
            background: #28365F;
            border-color: #28365F;
        }

        .relatorios-card .tab-content {
            padding: 1rem;
        }

        .relatorios-painel-titulo {
            margin: 0 0 0.85rem;
            color: #1f2d4f;
            font-size: 1rem;
            font-weight: 700;
        }

        .relatorios-painel-texto {
            margin: 0 0 0.9rem;
            color: #5f6b85;
            font-size: 0.86rem;
        }

        .relatorio-tabela-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .relatorio-tabela {
            width: 100%;
            min-width: 860px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .relatorio-tabela thead th {
            background: #f47a2a;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #d8dee9;
            padding: 0.7rem 0.8rem;
            text-align: center;
            white-space: nowrap;
        }

        .relatorio-tabela tbody td,
        .relatorio-tabela tfoot td {
            background: #fff;
            color: #263248;
            border: 1px solid #d8dee9;
            padding: 0.7rem 0.8rem;
            text-align: center;
            min-width: 62px;
        }

        .relatorio-tabela .coluna-projeto {
            min-width: 220px;
            text-align: left;
            font-weight: 600;
            background: #1f2d4f;
            color: #fff;
        }

        .relatorio-tabela thead th:last-child,
        .relatorio-tabela tbody td:last-child,
        .relatorio-tabela tfoot td:last-child {
            min-width: 88px;
            font-weight: 700;
        }

        .relatorio-tabela tbody tr td:last-child,
        .relatorio-tabela tfoot td {
            background: #eef2f7;
            color: #1f2d4f;
        }

        .relatorio-tabela tfoot td {
            font-weight: 700;
        }

        .relatorio-vazio {
            margin: 0;
            color: #6a748a;
        }

        @media (max-width: 575.98px) {
            .relatorios-topo-acoes {
                flex-direction: column;
                align-items: stretch;
                padding: 0.75rem 0.8rem;
            }

            .relatorios-acoes-botoes {
                flex-direction: column;
                align-items: stretch;
            }

            .relatorios-card .tab-content {
                padding: 0.8rem;
            }

            .relatorios-voltar {
                margin-top: 0;
            }

            .relatorio-tabela {
                min-width: 760px;
            }

            .relatorio-tabela thead th,
            .relatorio-tabela tbody td,
            .relatorio-tabela tfoot td {
                padding: 0.6rem 0.7rem;
                font-size: 0.88rem;
            }

            .relatorio-tabela .coluna-projeto {
                min-width: 180px;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .relatorios-shell {
                max-width: 920px;
            }
        }

        @media (min-width: 1200px) {
            .relatorios-shell {
                max-width: 1220px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="relatorios-shell">
        <div class="relatorios-topo">
            <div class="relatorios-topo-acoes">
                <p class="relatorios-topo-texto">
                    Consulte os relatorios gerais por idade e sexo ou abra o painel de pendencias por instituicao.
                </p>

                <div class="relatorios-acoes-botoes">
                    <a href="{{ route('admin.relatorios.alertas') }}" class="relatorios-pendencias-btn">
                        <i class="bi bi-bell"></i>
                        Alertas administrativos
                    </a>

                    <a href="{{ route('admin.relatorios.comparativo') }}" class="relatorios-pendencias-btn">
                        <i class="bi bi-arrow-left-right"></i>
                        Comparar instituicoes
                    </a>

                    <a href="{{ route('admin.relatorios.pendencias') }}" class="relatorios-pendencias-btn">
                        <i class="bi bi-exclamation-diamond"></i>
                        Painel de pendencias
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary relatorios-voltar">
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        <div class="relatorios-card">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#relatorio-masculino"
                        type="button" role="tab">
                        Masculino
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#relatorio-feminino"
                        type="button" role="tab">
                        Feminino
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="relatorio-masculino" role="tabpanel">
                    <h2 class="relatorios-painel-titulo">Atletas por projeto</h2>
                    <p class="relatorios-painel-texto">Linhas por projeto e colunas agrupadas por idade.</p>
                    @include('admin.relatorios.partials.tabela', [
                        'idades' => $idadesMasculino,
                        'relatorio' => $relatorioMasculino,
                    ])
                </div>

                <div class="tab-pane fade" id="relatorio-feminino" role="tabpanel">
                    <h2 class="relatorios-painel-titulo">Atletas por projeto</h2>
                    <p class="relatorios-painel-texto">Linhas por projeto e colunas agrupadas por idade.</p>
                    @include('admin.relatorios.partials.tabela', [
                        'idades' => $idadesFeminino,
                        'relatorio' => $relatorioFeminino,
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
