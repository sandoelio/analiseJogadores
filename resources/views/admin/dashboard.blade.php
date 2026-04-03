@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .admin-dashboard-shell {
            max-width: 980px;
            width: 100%;
            margin: 0 auto;
            padding: 1.15rem 0 1rem;
        }

        .admin-dashboard-header {
            margin-bottom: 1rem;
        }

        .admin-dashboard-intro {
            border: 1px solid #d8dde8;
            border-radius: 1rem;
            background:
                linear-gradient(135deg, rgba(17, 27, 51, 0.96) 0%, rgba(34, 49, 86, 0.94) 100%);
            box-shadow: 0 10px 24px rgba(17, 27, 51, 0.14);
            padding: 1.25rem 1.35rem;
            color: #fff;
        }

        .admin-dashboard-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin-bottom: 0.7rem;
            padding: 0.32rem 0.72rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: #f4f7ff;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .admin-dashboard-title {
            margin: 0;
            font-size: 1.72rem;
            font-weight: 700;
            line-height: 1.18;
        }

        .admin-dashboard-subtitle {
            max-width: 720px;
            margin: 0.5rem 0 0;
            color: rgba(244, 247, 255, 0.9);
            font-size: 0.98rem;
            line-height: 1.5;
        }

        .admin-dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .admin-dashboard-btn {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: space-between;
            min-height: 152px;
            padding: 1.05rem;
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
            text-decoration: none;
            color: #1f2d4f;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }

        .admin-dashboard-btn:hover {
            transform: translateY(-2px);
            border-color: #8da0c9;
            color: #1f2d4f;
            box-shadow: 0 10px 22px rgba(26, 42, 80, 0.12);
        }

        .admin-dashboard-btn-icon {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.95rem;
            font-size: 1.45rem;
        }

        .admin-dashboard-btn-body {
            display: grid;
            gap: 0.35rem;
            width: 100%;
        }

        .admin-dashboard-btn-title {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .admin-dashboard-btn-text {
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .admin-dashboard-btn-meta {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .admin-dashboard-btn-primary {
            background: linear-gradient(180deg, #ffffff 0%, #f7f9fd 100%);
        }

        .admin-dashboard-btn-primary .admin-dashboard-btn-icon {
            background: #e9eef9;
            color: #28365F;
        }

        .admin-dashboard-btn-primary .admin-dashboard-btn-meta {
            background: #eef3fb;
            color: #28365F;
        }

        .admin-dashboard-btn-accent {
            background: linear-gradient(180deg, #fff8f1 0%, #ffffff 100%);
            border-color: #f0c29a;
        }

        .admin-dashboard-btn-accent .admin-dashboard-btn-icon {
            background: #f47a2a;
            color: #fff;
        }

        .admin-dashboard-btn-accent .admin-dashboard-btn-meta {
            background: #fff0e3;
            color: #d46317;
        }

        .admin-dashboard-btn-secondary {
            background: #f8fafc;
        }

        .admin-dashboard-btn-secondary .admin-dashboard-btn-icon {
            background: #e7edf6;
            color: #4c5f87;
        }

        .admin-dashboard-btn-secondary .admin-dashboard-btn-meta {
            background: #edf2f8;
            color: #4c5f87;
        }

        @media (min-width: 992px) {
            .admin-dashboard-shell {
                max-width: 920px;
                padding-top: 1.3rem;
            }
        }

        @media (max-width: 991.98px) {
            .admin-dashboard-shell {
                max-width: 760px;
            }

            .admin-dashboard-title {
                font-size: 1.46rem;
            }
        }

        @media (max-width: 576px) {
            .admin-dashboard-shell {
                max-width: 400px;
                padding-top: 0.4rem;
            }

            .admin-dashboard-header {
                margin-bottom: 0.75rem;
            }

            .admin-dashboard-intro {
                padding: 0.85rem 0.9rem;
                border-radius: 0.9rem;
            }

            .admin-dashboard-kicker {
                margin-bottom: 0.4rem;
                font-size: 0.72rem;
            }

            .admin-dashboard-title {
                font-size: 1.08rem;
            }

            .admin-dashboard-subtitle {
                display: none;
            }

            .admin-dashboard-grid {
                display: flex;
                flex-direction: column;
                gap: 0.55rem;
                margin-top: 0.35rem;
            }

            .admin-dashboard-btn {
                min-height: 108px;
                padding: 0.8rem 0.9rem;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.16) 0%, rgba(210, 227, 255, 0.08) 100%);
                border: 1px solid rgba(255, 255, 255, 0.22);
                box-shadow: 0 10px 20px rgba(15, 24, 44, 0.14);
                align-items: center;
                text-align: center;
                color: #fff !important;
                border-radius: 1rem;
            }

            .admin-dashboard-btn:hover {
                transform: none;
                border-color: rgba(255, 255, 255, 0.18);
                box-shadow: 0 8px 18px rgba(15, 24, 44, 0.12);
                color: #fff !important;
            }

            .admin-dashboard-btn-icon {
                width: 42px;
                height: 42px;
                background: rgba(255, 255, 255, 0.14) !important;
                color: #fff !important;
                font-size: 1.2rem;
                border-radius: 0.85rem;
            }

            .admin-dashboard-btn-body {
                gap: 0.14rem;
            }

            .admin-dashboard-btn-title {
                font-size: 0.98rem;
            }

            .admin-dashboard-btn-text {
                display: block;
                color: rgba(255, 255, 255, 0.84);
                font-size: 0.76rem;
                line-height: 1.35;
            }

            .admin-dashboard-btn-meta {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="admin-dashboard-shell">
        <div class="admin-dashboard-header">
            <div class="admin-dashboard-intro">
                <span class="admin-dashboard-kicker">
                    <i class="bi bi-shield-lock-fill"></i>
                    Modulo Administrativo
                </span>
                <p class="admin-dashboard-subtitle">
                    Gerencie usuarios e acompanhe relatorios de desempenho dos projetos para obter insights valiosos e tomar decisões informadas.
                </p>
            </div>
        </div>

        <div class="admin-dashboard-grid">
            <a href="{{ route('admin.relatorios') }}" class="admin-dashboard-btn admin-dashboard-btn-accent">
                <span class="admin-dashboard-btn-icon">
                    <i class="bi bi-bar-chart-fill"></i>
                </span>
                <span class="admin-dashboard-btn-body">
                    <span class="admin-dashboard-btn-title">Relatorio</span>
                    <span class="admin-dashboard-btn-text">Visualizar os dados gerais por projeto, sexo e idade.</span>
                </span>
                <span class="admin-dashboard-btn-meta">Painel gerencial</span>
            </a>

            <a href="{{ route('usuarios.create') }}" class="admin-dashboard-btn admin-dashboard-btn-primary">
                <span class="admin-dashboard-btn-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </span>
                <span class="admin-dashboard-btn-body">
                    <span class="admin-dashboard-btn-title">Novo Usuario</span>
                    <span class="admin-dashboard-btn-text">Cadastrar novos acessos administrativos e tecnicos.</span>
                </span>
                <span class="admin-dashboard-btn-meta">Cadastro</span>
            </a>

            <a href="{{ route('usuarios.index') }}" class="admin-dashboard-btn admin-dashboard-btn-primary">
                <span class="admin-dashboard-btn-icon">
                    <i class="bi bi-people-fill"></i>
                </span>
                <span class="admin-dashboard-btn-body">
                    <span class="admin-dashboard-btn-title">Listar Usuarios</span>
                    <span class="admin-dashboard-btn-text">Consultar, revisar e manter os usuarios ja cadastrados.</span>
                </span>
                <span class="admin-dashboard-btn-meta">Gestao de acesso</span>
            </a>

            <a href="{{ route('public.dashboard') }}" class="admin-dashboard-btn admin-dashboard-btn-secondary">
                <span class="admin-dashboard-btn-icon">
                    <i class="bi bi-house-door-fill"></i>
                </span>
                <span class="admin-dashboard-btn-body">
                    <span class="admin-dashboard-btn-title">Pagina Inicial</span>
                    <span class="admin-dashboard-btn-text">Retornar para a navegacao geral publica do sistema.</span>
                </span>
                <span class="admin-dashboard-btn-meta">Navegacao</span>
            </a>
        </div>
    </div>
@endsection
