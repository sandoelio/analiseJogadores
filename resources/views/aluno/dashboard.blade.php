{{-- resources/views/aluno/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Usuario')

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
            min-height: calc(90vh - 10rem - 12rem);
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
            border-color: #28365F;
            color: #28365F;
        }

        .dashboard-btn i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .back-logo {
            background: #28365F;
            
        }
    </style>
@endpush

@section('content')

    {{-- Logo --}}   
    <div class="text-center mb-0">
      <img 
        src="{{ asset('imagem/LOGO1.png') }}" 
        alt="Cesta Baiana" 
        style="max-width: 200px; width: 100%; height: auto; margin-top: 5%;"
        class="back-logo"
        loading="lazy"
      >
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

            <a href="{{route("aluno.index")}} " class="dashboard-btn">
                <i class="bi bi-people-fill"></i>
                Atletas Cadastrados
            </a>

            <a href="{{ route('public.dashboard') }}" class="dashboard-btn">
                <i class="bi bi-house-door me-1"></i>
                Página Inicial
            </a>
        </div>
    </div>
@endsection
