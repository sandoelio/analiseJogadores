@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .resultado-shell {
            max-width: 1120px;
            margin: 0 auto;
            padding: 1rem 0 1.2rem;
        }

        .resultado-topo {
            margin-bottom: 0.95rem;
        }

        .resultado-heading,
        .resultado-card,
        .evento-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .resultado-heading {
            padding: 1rem 1.1rem;
        }

        .resultado-chip {
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

        .resultado-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .resultado-text {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .resultado-confronto {
            margin-top: 0.95rem;
        }

        .resultado-confronto-linha {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .resultado-time {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-height: 40px;
            padding: 0.45rem 0.8rem;
            border-radius: 999px;
            background: #f5f8fd;
            border: 1px solid #dbe1ec;
            color: #223154;
            font-weight: 700;
        }

        .resultado-time small {
            color: #6a7690;
            font-weight: 600;
        }

        .resultado-vs {
            color: #6a7690;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .resultado-card {
            overflow: hidden;
        }

        .resultado-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.95rem 1.05rem;
            border-bottom: 1px solid #edf2f8;
        }

        .resultado-card-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.04rem;
            font-weight: 700;
        }

        .resultado-card-subtitle {
            margin: 0.18rem 0 0;
            color: #5f6b85;
            font-size: 0.84rem;
        }

        .resultado-badge {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #f5f8fd;
            border: 1px solid #dbe1ec;
            color: #44506b;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .resultado-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.9rem;
            padding: 1rem;
        }

        .evento-card {
            position: relative;
            padding: 1rem 0.95rem 0.95rem;
            background: #fbfcfe;
        }

        .evento-numero {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background: #28365F;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .evento-numero.final {
            background: #1f2d4f;
        }

        .evento-titulo {
            margin: 0 2.35rem 0.75rem 0;
            color: #223154;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .evento-lista {
            margin: 0;
            padding-left: 1rem;
            color: #44506b;
        }

        .evento-lista li + li {
            margin-top: 0.4rem;
        }

        .resultado-acoes {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .resultado-btn {
            min-height: 44px;
            padding: 0.6rem 1.2rem;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .resultado-btn-principal {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .resultado-btn-principal:hover,
        .resultado-btn-principal:focus {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .resultado-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .resultado-shell {
                padding-top: 0.55rem;
            }

            .resultado-title {
                font-size: 1.22rem;
            }

            .resultado-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.55rem;
            }

            .resultado-confronto-linha {
                gap: 0.4rem;
            }

            .resultado-time {
                min-height: 36px;
                padding: 0.38rem 0.65rem;
                font-size: 0.82rem;
                white-space: nowrap;
            }

            .resultado-vs {
                white-space: nowrap;
            }

            .resultado-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                padding: 0.85rem;
            }

            .resultado-acoes {
                flex-direction: column;
                align-items: stretch;
            }

            .resultado-btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $eventCount = count($eventos);
        $showFinal = $eventCount < config('comparativo.eventos') + 2;
    @endphp

    <div class="container-fluid resultado-shell">
        <div class="resultado-topo">
            <div class="resultado-heading">
                <span class="resultado-chip">
                    <i class="bi bi-chat-square-text"></i>
                    Narracao
                </span>
                <div class="resultado-confronto">
                    <h1 class="resultado-title">Resultado do duelo</h1>
                    <div class="resultado-confronto-linha">
                        <span class="resultado-time">
                            {{ $aluno1->nome }}
                        </span>
                        <span class="resultado-vs">vs</span>
                        <span class="resultado-time">
                            {{ $aluno2->nome }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="resultado-card">
            <div class="resultado-card-header">
                <div>
                    <h2 class="resultado-card-title">Eventos da partida</h2>
                </div>

                <span class="resultado-badge">
                    {{ $eventCount + ($showFinal ? 1 : 0) }} blocos
                </span>
            </div>

            <div class="resultado-grid">
                @foreach ($eventos as $idx => $linhas)
                    <article class="evento-card">
                        <span class="evento-numero">{{ $idx + 1 }}</span>
                        <h3 class="evento-titulo">Momento {{ $idx + 1 }}</h3>
                        <ul class="evento-lista">
                            @foreach ($linhas as $linha)
                                <li>{!! nl2br($linha) !!}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach

                @if ($showFinal)
                    <article class="evento-card">
                        <span class="evento-numero final">{{ $eventCount + 1 }}</span>
                        <h3 class="evento-titulo">Fechamento da partida</h3>
                        <ul class="evento-lista">
                            <li><strong>Fim de partida</strong></li>
                            <li>
                                Placar final:
                                <strong>{{ $aluno1->nome }}</strong>
                                {{ $placar[$aluno1->nome] }}
                                x
                                {{ $placar[$aluno2->nome] }}
                                <strong>{{ $aluno2->nome }}</strong>
                            </li>
                        </ul>
                    </article>
                @endif
            </div>
        </div>

        <div class="resultado-acoes">
            <a href="{{ route('public.dashboard') }}" class="btn btn-outline-secondary resultado-btn">
                Voltar
            </a>
            <a href="{{ route('comparar.index') }}" class="btn resultado-btn resultado-btn-principal">
                <i class="bi bi-arrow-repeat me-1"></i>
                Gerar novo duelo
            </a>
        </div>
    </div>
@endsection
