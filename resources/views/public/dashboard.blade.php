{{-- resources/views/public/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden !important;
        }

        .dashboard-shell {
            max-width: 780px;
            width: 100%;
            margin: 0 auto;
            padding: 0.85rem 0 0.8rem;
        }

        .dashboard-hero {
            display: block;
            margin-bottom: 0.8rem;
        }

        .dashboard-intro {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .dashboard-intro {
            padding: 1rem 1.1rem;
        }

        .dashboard-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-bottom: 0.5rem;
            padding: 0.28rem 0.6rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .dashboard-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.42rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .dashboard-text {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.8rem;
        }

        .dashboard-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.8rem;
            min-height: 138px;
            padding: 0.9rem 1rem;
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
            text-decoration: none;
            color: #1f2d4f;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            border-color: #9fb2d7;
            color: #1f2d4f;
            box-shadow: 0 10px 24px rgba(26, 42, 80, 0.12);
        }

        .dashboard-card-primary {
            grid-column: 1 / -1;
            min-height: 148px;
            background: linear-gradient(135deg, #fff 0%, #f6f9ff 100%);
        }

        .dashboard-card-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            background: #eef3fb;
            color: #28365F;
            font-size: 1.3rem;
        }

        .dashboard-card-primary .dashboard-card-icon {
            background: #28365F;
            color: #fff;
        }

        .dashboard-card-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .dashboard-card-text {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.86rem;
            line-height: 1.4;
        }

        .dashboard-card-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #28365F;
            font-size: 0.84rem;
            font-weight: 700;
        }

        @media (min-width: 577px) and (max-width: 991.98px) {
            .dashboard-shell {
                max-width: 700px;
            }

            .dashboard-title {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-shell {
                max-width: 500px;
                padding-top: 0.4rem;
            }

            .dashboard-hero {
                display: none;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .dashboard-card,
            .dashboard-card-primary {
                min-height: auto;
                padding: 0.8rem;
                background: transparent;
                border: none;
                box-shadow: none;
                align-items: center;
                text-align: center;
                color: #fff !important;
            }

            .dashboard-card:hover {
                transform: none;
                border: none;
                box-shadow: none;
                color: #28365F !important;
            }

            .dashboard-card-icon {
                width: auto;
                height: auto;
                background: transparent !important;
                color: #fff !important;
                font-size: 1.6rem;
            }

            .dashboard-card-title {
                font-size: 1rem;
            }

            .dashboard-card-text {
                font-size: 0.82rem;
                color: rgba(255, 255, 255, 0.85);
            }

            .dashboard-card-link {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-shell">
        <div class="dashboard-hero">
            <div class="dashboard-intro">
                <span class="dashboard-chip">
                    <i class="bi bi-compass-fill"></i>
                    Painel de Consulta
                </span>
                <h4>Escolha como deseja visualizar e comparar o desempenho</h4>
                <p class="dashboard-text">
                    A analise individual e o caminho principal. As comparacoes ficam logo abaixo para consultas
                    narrativas e graficas.
                </p>
            </div>
        </div>

        <div class="dashboard-grid">
            <a href="{{ route('analise.index') }}" class="dashboard-card dashboard-card-primary">
                <div>
                    <span class="dashboard-card-icon">
                        <i class="bi bi-pie-chart-fill"></i>
                    </span>
                    <h2 class="dashboard-card-title mt-3">Analise Individual</h2>
                    <p class="dashboard-card-text">
                        Consulte o atleta, abra o historico recente e visualize os dados em um unico fluxo.
                    </p>
                </div>
                <span class="dashboard-card-link">
                    Abrir analise
                    <i class="bi bi-arrow-right"></i>
                </span>
            </a>

            <a href="{{ route('comparar.index') }}" class="dashboard-card">
                <div>
                    <span class="dashboard-card-icon">
                        <i class="bi bi-chat-dots-fill"></i>
                    </span>
                    <h2 class="dashboard-card-title mt-3">1x1 Narrativo</h2>
                    <p class="dashboard-card-text">
                        Compare dois atletas com leitura textual mais direta para revisao rapida.
                    </p>
                </div>
                <span class="dashboard-card-link">
                    Abrir comparacao
                    <i class="bi bi-arrow-right"></i>
                </span>
            </a>

            <a href="{{ route('comparar.grafico.index') }}" class="dashboard-card">
                <div>
                    <span class="dashboard-card-icon">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </span>
                    <h2 class="dashboard-card-title mt-3">1x1 Grafico</h2>
                    <p class="dashboard-card-text">
                        Veja a comparacao visual entre atletas com apoio grafico.
                    </p>
                </div>
                <span class="dashboard-card-link">
                    Abrir grafico
                    <i class="bi bi-arrow-right"></i>
                </span>
            </a>
        </div>
    </div>
@endsection
