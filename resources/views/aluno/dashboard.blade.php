{{-- resources/views/aluno/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise de Desempenhos')

@push('styles')
    <style>
        .dashboard-shell {
            max-width: 960px;
            width: 100%;
            margin: 0 auto;
            padding: 1.15rem 0 1rem;
        }

        .dashboard-header {
            display: block;
            margin-bottom: 1rem;
        }

        .dashboard-intro {
            border: 1px solid #dbe1ec;
            border-radius: 0.9rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
        }

        .dashboard-intro {
            flex: 1 1 auto;
            padding: 1.25rem 1.4rem;
        }

        .dashboard-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.65rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .dashboard-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.7rem;
            font-weight: 700;
        }

        .dashboard-subtitle {
            margin: 0.45rem 0 0;
            color: #5f6b85;
            font-size: 0.98rem;
            line-height: 1.5;
        }

        .dashboard-buttons {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            width: 100%;
        }

        .dashboard-btn-span-2 {
            grid-column: span 2;
        }

        .dashboard-btn {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: space-between;
            min-height: 138px;
            padding: 1rem;
            border: 1px solid #dbe1ec;
            border-radius: 0.9rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
            text-decoration: none;
            color: #1f2d4f;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }

        .dashboard-btn:hover {
            transform: translateY(-2px);
            border-color: #9fb2d7;
            color: #1f2d4f;
            box-shadow: 0 8px 20px rgba(26, 42, 80, 0.10);
        }

        .dashboard-btn-icon {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.9rem;
            background: #eef3fb;
            color: #28365F;
            font-size: 1.45rem;
        }

        .dashboard-btn-body {
            display: grid;
            gap: 0.35rem;
            width: 100%;
        }

        .dashboard-btn-title {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .dashboard-btn-text {
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .dashboard-btn-primary .dashboard-btn-icon {
            background: #eef3fb;
            color: #28365F;
        }

        .dashboard-btn-accent {
            background: linear-gradient(135deg, #fff7ef 0%, #ffffff 100%);
            border-color: #f3c299;
        }

        .dashboard-btn-accent .dashboard-btn-icon {
            background: #f47a2a;
            color: #fff;
        }

        .dashboard-btn-secondary {
            background: #f8fafc;
        }

        .dashboard-btn-secondary .dashboard-btn-icon {
            background: #e8edf5;
            color: #4c5f87;
        }

        @media (min-width: 577px) and (max-width: 991.98px) {
            .dashboard-shell {
                max-width: 760px;
            }

            .dashboard-title {
                font-size: 1.45rem;
            }

            .dashboard-buttons {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-btn-span-2 {
                grid-column: auto;
            }

            .dashboard-btn {
                min-height: 126px;
            }
        }

        @media (min-width: 992px) {
            .dashboard-shell {
                max-width: 900px;
                padding-top: 1.35rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-shell {
                max-width: 400px;
                padding-top: 0.6rem;
            }

            .dashboard-header {
                display: block;
                margin-bottom: 0.75rem;
            }

            .dashboard-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
                margin-top: 10px;
            }

            .dashboard-buttons a {
                background: transparent;
                border: none;
                box-shadow: none;
                color: #fff !important;
            }

            .dashboard-btn {
                min-height: auto;
                padding: 0.8rem;
                background: transparent;
                border: none;
                align-items: center;
                text-align: center;
            }

            .dashboard-btn:hover {
                transform: none;
                border: none;
                box-shadow: none;
                color: #28365F !important;
            }

            .dashboard-btn-icon {
                width: auto;
                height: auto;
                background: transparent !important;
                color: #fff !important;
                font-size: 1.6rem;
            }

            .dashboard-btn-body {
                gap: 0.2rem;
            }

            .dashboard-btn-title {
                font-size: 1rem;
            }

            .dashboard-btn-text {
                font-size: 0.82rem;
                color: rgba(255, 255, 255, 0.85);
            }

            .dashboard-btn-span-2 {
                grid-column: auto;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-shell">
        <div class="dashboard-header">
            <div class="dashboard-intro">
                <span class="dashboard-kicker">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Painel do Técnico
                </span>
                <h1 class="dashboard-title">Acesso rápido às ações principais</h1>
                <p class="dashboard-subtitle">
                    Cadastre atletas, lance novas avaliações, consulte a base da instituição e acompanhe os relatórios
                    em um fluxo mais direto para notebook e desktop.
                </p>
            </div>
        </div>

        <div class="container-fluid dashboard-container px-0">
            <div class="dashboard-buttons">
                <a href="{{ route('aluno.create') }}" class="dashboard-btn dashboard-btn-primary">
                    <span class="dashboard-btn-icon">
                        <i class="bi bi-person-plus-fill"></i>
                    </span>
                    <span class="dashboard-btn-body">
                        <span class="dashboard-btn-title">Novo Atleta</span>
                        <span class="dashboard-btn-text">Cadastrar atleta e registrar a primeira análise.</span>
                    </span>
                </a>

                <a href="{{ route('aluno.updateForm') }}" class="dashboard-btn dashboard-btn-primary">
                    <span class="dashboard-btn-icon">
                        <i class="bi bi-pencil-square"></i>
                    </span>
                    <span class="dashboard-btn-body">
                        <span class="dashboard-btn-title">Atualizar Atleta</span>
                        <span class="dashboard-btn-text">Lançar nova avaliação e manter o histórico do atleta.</span>
                    </span>
                </a>

                <a href="{{ route('aluno.index') }}" class="dashboard-btn dashboard-btn-primary">
                    <span class="dashboard-btn-icon">
                        <i class="bi bi-people-fill"></i>
                    </span>
                    <span class="dashboard-btn-body">
                        <span class="dashboard-btn-title">Atletas Cadastrados</span>
                        <span class="dashboard-btn-text">Consultar, editar e revisar os atletas da instituição.</span>
                    </span>
                </a>

                <a href="{{ route('tecnico.relatorios') }}" class="dashboard-btn dashboard-btn-accent dashboard-btn-span-2">
                    <span class="dashboard-btn-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </span>
                    <span class="dashboard-btn-body">
                        <span class="dashboard-btn-title">Relatórios</span>
                        <span class="dashboard-btn-text">Visualizar dados consolidados por idade e sexo.</span>
                    </span>
                </a>

                <a href="{{ route('public.dashboard') }}" class="dashboard-btn dashboard-btn-secondary">
                    <span class="dashboard-btn-icon">
                        <i class="bi bi-house-door"></i>
                    </span>
                    <span class="dashboard-btn-body">
                        <span class="dashboard-btn-title">Página Inicial</span>
                        <span class="dashboard-btn-text">Retornar para a navegação geral do sistema.</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
@endsection
