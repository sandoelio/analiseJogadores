{{-- resources/views/public/home.blade.php --}}
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

        .site-navbar-toggler,
        .site-navbar-menu {
            display: none !important;
        }

        .home-shell {
            max-width: 940px;
            width: 100%;
            margin: 0 auto;
            padding: 0.95rem 0 1rem;
        }

        .home-hero {
            margin-bottom: 0.9rem;
            padding: 1.05rem 1.15rem;
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .home-chip {
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

        .home-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .home-text {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.92rem;
            line-height: 1.45;
            max-width: 760px;
        }

        .home-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .home-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.85rem;
            min-height: 168px;
            padding: 1rem;
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
            text-decoration: none;
            color: #1f2d4f;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }

        .home-card:hover {
            transform: translateY(-2px);
            border-color: #9fb2d7;
            color: #1f2d4f;
            box-shadow: 0 10px 24px rgba(26, 42, 80, 0.12);
        }

        .home-card-destaque {
            background: linear-gradient(135deg, #fff 0%, #f6f9ff 100%);
            border-color: #bfd0ee;
        }

        .home-card-icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.9rem;
            background: #eef3fb;
            color: #28365F;
            font-size: 1.3rem;
        }

        .home-card-destaque .home-card-icon {
            background: #28365F;
            color: #fff;
        }

        .home-card-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .home-card-text {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.87rem;
            line-height: 1.45;
        }

        .home-card-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #28365F;
            font-size: 0.84rem;
            font-weight: 700;
        }

        @media (min-width: 577px) and (max-width: 991.98px) {
            .home-shell {
                max-width: 760px;
            }

            .home-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .home-card-destaque {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 576px) {
            .home-shell {
                max-width: 500px;
                padding-top: 0.4rem;
            }

            .home-hero {
                padding: 0.9rem;
                margin-bottom: 0.75rem;
                border-radius: 0.95rem;
                box-shadow: 0 8px 18px rgba(15, 24, 44, 0.12);
            }

            .home-title {
                font-size: 1.2rem;
            }

            .home-text {
                display: none;
            }

            .home-grid {
                grid-template-columns: 1fr;
                gap: 0.55rem;
            }

            .home-card {
                min-height: 118px;
                padding: 0.85rem 0.9rem;
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.16) 0%, rgba(210, 227, 255, 0.08) 100%);
                border-color: rgba(255, 255, 255, 0.22);
                box-shadow: 0 10px 20px rgba(15, 24, 44, 0.14);
                color: #fff !important;
            }

            .home-card:hover {
                transform: none;
                border-color: rgba(255, 255, 255, 0.18);
                box-shadow: 0 8px 18px rgba(15, 24, 44, 0.12);
                color: #fff !important;
            }

            .home-card-destaque {
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(169, 199, 255, 0.12) 100%);
                border-color: rgba(255, 255, 255, 0.26);
            }

            .home-card-icon {
                width: 42px;
                height: 42px;
                background: rgba(255, 255, 255, 0.14) !important;
                color: #fff !important;
                border-radius: 0.85rem;
                font-size: 1.15rem;
            }

            .home-card-title {
                margin-top: 0.55rem !important;
                font-size: 0.98rem;
                color: #fff;
            }

            .home-card-text {
                color: rgba(255, 255, 255, 0.84);
                font-size: 0.77rem;
                line-height: 1.35;
            }

            .home-card-link {
                color: #fff;
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="home-shell">
        <div class="home-hero">
            <span class="home-chip">
                <i class="bi bi-door-open-fill"></i>
                Entrada do Sistema
            </span>
            <h1 class="home-title">Escolha o perfil de acesso</h1>
        </div>

        <div class="home-grid">
            <a href="{{ route('aluno.login') }}" class="home-card home-card-destaque">
                <div>
                    <span class="home-card-icon">
                        <i class="bi bi-people-fill"></i>
                    </span>
                    <h2 class="home-card-title mt-3">Acesso Atleta</h2>
                    <p class="home-card-text">
                        Entre para consultar desempenho, histórico e comparações disponíveis para o seu perfil.
                    </p>
                </div>
                <span class="home-card-link">
                    Entrar como atleta
                    <i class="bi bi-arrow-right"></i>
                </span>
            </a>

            <a href="{{ route('login') }}" class="home-card">
                <div>
                    <span class="home-card-icon">
                        <i class="bi bi-person-video2"></i>
                    </span>
                    <h2 class="home-card-title mt-3">Acesso Técnico</h2>
                    <p class="home-card-text">
                        Cadastre atletas, atualize avaliações e consulte os relatórios da instituição vinculada.
                    </p>
                </div>
                <span class="home-card-link">
                    Entrar como técnico
                    <i class="bi bi-arrow-right"></i>
                </span>
            </a>

            <a href="{{ route('login') }}" class="home-card">
                <div>
                    <span class="home-card-icon">
                        <i class="bi bi-person-fill-gear"></i>
                    </span>
                    <h2 class="home-card-title mt-3">Acesso Administrativo</h2>
                    <p class="home-card-text">
                        Gerencie usuários, instituições e acompanhe os relatórios gerais do sistema.
                    </p>
                </div>
                <span class="home-card-link">
                    Entrar como administrador
                    <i class="bi bi-arrow-right"></i>
                </span>
            </a>
        </div>
    </div>
@endsection
