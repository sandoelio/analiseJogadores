@extends('layouts.app')

@section('title', 'Login')

@push('styles')
    <style>
        /* Centraliza verticalmente e horizontalmente */
        .login-container {
            min-height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Cabeçalho customizado */
        .login-header {
            background-color: #28365F !important;
        }

        /* Botão customizado */
        .login-btn {
            background-color: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .login-btn:hover,
        .login-btn:focus {
            background-color: #FF7209
;
            border-color: #152147;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid login-container">

        <div class="col-12 col-sm-10 col-md-8 col-lg-5">
            <div class="card shadow-sm">

                <div class="card-header text-center login-header text-white">
                    <h2 class="h5 mb-0">Entrar no Sistema</h2>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn login-btn">Entrar</button>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('analise.index') }}" class="btn btn-secondary">
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
