{{-- resources/views/aluno/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Alunos')

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
            /* desconta header e footer */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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
            border-color: #1B265E;
            color: #1B265E;
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
            <a href="{{ route('aluno.create') }}" class="dashboard-btn">
                <i class="bi bi-person-plus-fill"></i>
                Novo Atleta
            </a>

            <a href="{{ route('aluno.updateForm') }}" class="dashboard-btn">
                <i class="bi bi-pencil-square"></i>
                Atualizar Atleta
            </a>

            <a href="{{route("aluno.index")}} " class="dashboard-btn">
                <i class="bi bi-people-fill"></i>
                Atletas Cadastrados
            </a>

            <a href="{{ route('analise.index') }}" class="dashboard-btn">
                <i class="bi bi-arrow-left-circle-fill"></i>
                Voltar
            </a>
        </div>
    </div>
@endsection
