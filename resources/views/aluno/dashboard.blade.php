{{-- resources/views/aluno/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise de Desempenhos')

@push('styles')
    <style>
        .dashboard-shell {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
        }

        .dashboard-buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            width: 100%;
        }

        .dashboard-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.2rem;
            background: #fff;
            border: 2px solid #ddd;
            border-radius: 0.5rem;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: background 0.2s, border-color 0.2s;
        }

        .dashboard-btn i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        /* Logo com mesma largura do shell/botões */
        .logo-wrap {
            width: 94%;
            margin: 3% auto 0;
            background-color: #28365F;
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-bottom: 1.5rem !important;
        }

        .back-logo {
            display: block;
            width: 100%;
            height: auto;
            max-width: 100%;
            max-height: 120px;
            object-fit: contain;
        }

        /* Tablet */
        @media (min-width: 577px) and (max-width: 991.98px) {
            .back-logo {
                max-height: 140px;
            }
        }

        /* Mobile */
        @media (max-width: 576px) {
            .dashboard-shell {
                max-width: 400px;
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
                color: #fff !important;
            }

            .dashboard-btn {
                padding: 0.8rem;
                background: transparent;
                border: none;
            }

            .dashboard-btn i {
                font-size: 1.6rem;
                margin-bottom: 0.3rem;
            }

            /* Oculta logo e remove fundo no mobile */
            .logo-wrap {
                display: none;
                background: transparent !important;
                border-color: transparent !important;
                color: inherit !important;
                padding: 0;
                margin: 0;
            }

            .back-logo {
                max-height: 100px;
            }

            /* Hover no mobile apenas muda cor do texto */
            .dashboard-buttons a:hover {
                background: transparent;
                border: none;
                color: #28365F;
            }

              /* Ícones brancos no mobile */
            .dashboard-btn i {
                color: #fff !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-shell">
        {{-- Logo com mesma largura do shell/botões --}}
        <div class="logo-wrap text-center mb-0">
            <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" class="back-logo" loading="lazy">
        </div>

        <div class="container-fluid dashboard-container">
            <div class="dashboard-buttons">
                <a href="{{ route('aluno.create') }}" class="dashboard-btn">
                    <i class="bi bi-person-plus-fill"></i>
                    Novo Atleta
                </a>

                <a href="{{ route('aluno.updateForm') }}" class="dashboard-btn">
                    <i class="bi bi-pencil-square"></i>
                    Atualizar Atleta
                </a>

                <a href="{{ route('aluno.index') }}" class="dashboard-btn">
                    <i class="bi bi-people-fill"></i>
                    Atletas Cadastrados
                </a>

                <a href="{{ route('public.dashboard') }}" class="dashboard-btn">
                    <i class="bi bi-house-door me-1"></i>
                    Página Inicial
                </a>
            </div>
        </div>
    </div>
@endsection
