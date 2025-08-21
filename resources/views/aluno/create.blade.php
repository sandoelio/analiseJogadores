{{-- resources/views/aluno/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Novo Aluno / Análise')

@push('styles')
    <style>
        /* Deixa os inputs readonly com aparência desabilitada */
        input[readonly] {
            background-color: #e9ecef;
            opacity: 1;
            cursor: not-allowed;
        }

        
        /* Cor do navbar */
        .bg-navbar-blue {
            background-color: #28365F !important;
            color: #fff;
        }

        /* Botão Salvar com a mesma cor */
        .btn-navbar-blue {
            background-color: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .btn-navbar-blue:hover {
            background-color: #28365F;
            border-color: #28365F;
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            {{-- Cartão de Formulário --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-navbar-blue text-center">
                    <h5 class="mb-0">Novo Atleta</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('aluno.store') }}" method="POST">
                        @csrf

                        {{-- Apenas o nome fica editável --}}
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Atleta</label>
                            <input type="text" id="nome" name="nome" placeholder="Nome e sobrenome ou apelideo"
                                class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}"
                                required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campos de estatística com valor padrão '1' e readonly --}}
                        @foreach (['arremesso', 'passe', 'marcacao', 'bandeja', 'jogada', 'dominio'] as $campo)
                            <div class="mb-3">
                                <label for="{{ $campo }}" class="form-label">
                                    {{ ucfirst($campo === 'dominio' ? 'Domínio de Bola' : $campo) }}
                                </label>

                                {{-- Input oculto para envio --}}
                                <input type="hidden" name="{{ $campo }}" value="1">

                                {{-- Input visual readonly --}}
                                <input type="number" id="{{ $campo }}" class="form-control" value="1"
                                    min="0" max="100" readonly>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-navbar-blue">Salvar</button>
                            <a href="{{ route('aluno.dashboard') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
