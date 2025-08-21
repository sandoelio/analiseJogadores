@extends('layouts.app')

@section('title', 'Dashboard Admin')

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
            min-height: calc(100vh - 10rem - 4rem);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-buttons {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
            max-width: 400px;
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

        .dashboard-btn:hover {
            background: #f8f9fa;
            border-color: #28365F;
            color: #28365F;
        }

        .dashboard-btn i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid dashboard-container">
        <div class="dashboard-buttons">
            <a href="{{ route('usuarios.create') }}" class="dashboard-btn">
                <i class="bi bi-person-plus-fill"></i>
                Novo Usuário
            </a>

            <a href="{{ route('usuarios.index') }}" class="dashboard-btn">
                <i class="bi bi-people-fill"></i>
                Listar Usuários
            </a>

            <a href="{{ route('analise.index') }}" class="dashboard-btn">
                <i class="bi bi-arrow-left-circle-fill"></i>
                Análise de Atletas
            </a>
        </div>
    </div>
@endsection
