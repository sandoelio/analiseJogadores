@extends('layouts.app')

@section('title', 'Análise de Desempenhos')

@push('styles')
    <style>
        .site-container,
        .dashboard-container {
            overflow: hidden !important;
        }

        html,
        body {
            overflow-x: hidden;
        }

        .content-box {
            overflow: visible !important;
        }

        .dashboard-container {
            min-height: calc(100vh - 12rem - 4rem);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Grid de botões fixa em 2 colunas, com gap */
        .dashboard-buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
        }

        /* Em mobile, muda para 1 coluna */
        @media (max-width: 576px) {
            .dashboard-buttons {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            /* Oculta logo no mobile e remove cor de fundo */
            .dashboard-buttons .logo-wrapper {
                display: none;
                background: transparent !important;
                border-color: transparent !important;
                color: inherit !important;
            }

            /* Remove cor de fundo dos botões no mobile */
            .dashboard-buttons>* {
                background: transparent;
                border: none;
                padding: 1rem;
            }

            .dashboard-buttons a {
                background: transparent;
                border: none;
                color: #fff !important;
            }

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

        /* Estilo unificado para logo e botões */
        .dashboard-buttons>* {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: #fff;
            border: 2px solid #ddd;
            border-radius: 0.5rem;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: background 0.2s, border-color 0.2s, color 0.2s;
        }

        /* Hover idem para botões */
        .dashboard-buttons a:hover {
            background: #f8f9fa;
            border-color: #28365F;
            color: #28365F;
        }

        .dashboard-buttons .logo-wrapper {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        /* Logo responsiva */
        .dashboard-buttons .logo-wrapper img {
            max-width: 150px;
            width: 100%;
            height: auto;
        }

        /* Ícones dos botões */
        .dashboard-btn i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid dashboard-container">
        <div class="dashboard-buttons">

            {{-- Logo no grid (sem hover/link) --}}
            <div class="logo-wrapper">
                <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" loading="lazy">
            </div>

            <a href="{{ route('usuarios.create') }}" class="dashboard-btn">
                <i class="bi bi-person-plus-fill"></i>
                Novo Usuário
            </a>

            <a href="{{ route('usuarios.index') }}" class="dashboard-btn">
                <i class="bi bi-people-fill"></i>
                Listar Usuários
            </a>

            <a href="{{ route('public.dashboard') }}" class="dashboard-btn">
                <i class="bi bi-house-door-fill"></i>
                Página Inicial
            </a>

        </div>
    </div>
@endsection
