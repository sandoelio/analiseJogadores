@extends('layouts.app')

@section('title', 'Relatorios da Instituicao')

@push('styles')
    <style>
        .relatorios-shell {
            max-width: 1180px;
            width: 100%;
            margin: 0 auto;
            padding-bottom: 2rem;
        }

        .relatorios-topo {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .relatorios-titulo {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 700;
            color: #1e2b4f;
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

        .relatorio-tabela-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .relatorio-tabela {
            width: 100%;
            min-width: 720px;
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

        .relatorio-tabela tbody td {
            background: #fff;
            color: #263248;
            border: 1px solid #d8dee9;
            padding: 0.7rem 0.8rem;
            text-align: center;
            min-width: 62px;
        }

        .relatorio-tabela thead th:last-child,
        .relatorio-tabela tbody td:last-child {
            min-width: 88px;
            font-weight: 700;
        }

        .relatorio-tabela tbody tr td:last-child {
            background: #eef2f7;
            color: #1f2d4f;
        }

        .relatorio-vazio {
            margin: 0;
            color: #6a748a;
        }

        @media (max-width: 575.98px) {
            .relatorios-topo {
                flex-direction: column;
                align-items: stretch;
            }

            .relatorios-titulo {
                font-size: 1.15rem;
            }

            .relatorios-card .tab-content {
                padding: 0.8rem;
            }

            .relatorios-voltar {
                margin-top: 0;
            }

            .relatorio-tabela {
                min-width: 640px;
            }

            .relatorio-tabela thead th,
            .relatorio-tabela tbody td {
                padding: 0.6rem 0.7rem;
                font-size: 0.88rem;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .relatorios-shell {
                max-width: 920px;
            }
        }

        @media (min-width: 1200px) {
            .relatorios-shell {
                max-width: 1180px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="relatorios-shell">
        <div class="relatorios-topo">
            <div>
                <p class="relatorios-subtitulo">
                    Instituicao: {{ $instituicao->nome ?? 'Nao informada' }}
                </p>
            </div>

            <a href="{{ route('tecnico.dashboard') }}" class="btn btn-secondary relatorios-voltar">
                Voltar
            </a>
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
                    @include('tecnico.relatorios.partials.tabela', [
                        'idades' => $idadesMasculino,
                        'relatorio' => $relatorioMasculino,
                    ])
                </div>

                <div class="tab-pane fade" id="relatorio-feminino" role="tabpanel">
                    @include('tecnico.relatorios.partials.tabela', [
                        'idades' => $idadesFeminino,
                        'relatorio' => $relatorioFeminino,
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
