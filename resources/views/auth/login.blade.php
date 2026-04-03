@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .site-navbar-toggler,
        .site-navbar-menu {
            display: none !important;
        }

        .login-shell {
            max-width: 940px;
            width: 100%;
            margin: 0 auto;
            padding: 1rem 0 1.1rem;
        }

        .login-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(340px, 0.95fr);
            gap: 1rem;
            align-items: stretch;
        }

        .login-panel,
        .login-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .login-panel {
            padding: 1.2rem 1.25rem;
        }

        .login-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.65rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .login-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.55rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .login-text {
            margin: 0.45rem 0 0;
            color: #5f6b85;
            font-size: 0.94rem;
            line-height: 1.5;
        }

        .login-lista {
            display: grid;
            gap: 0.55rem;
            margin-top: 1rem;
        }

        .login-lista-item {
            display: flex;
            align-items: flex-start;
            gap: 0.55rem;
            color: #44506b;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .login-lista-item i {
            color: #28365F;
        }

        .login-card {
            overflow: hidden;
        }

        .login-card-header {
            padding: 1rem 1.1rem;
            background: linear-gradient(135deg, #28365F 0%, #40548c 100%);
            color: #fff;
        }

        .login-card-header h2 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .login-card-body {
            padding: 1.1rem;
        }

        .login-card-subtitle {
            margin: 0 0 1rem;
            color: #5f6b85;
            font-size: 0.9rem;
        }

        .login-form .form-label {
            color: #33405f;
            font-weight: 600;
        }

        .login-form .form-control {
            min-height: 46px;
            border-radius: 0.8rem;
            border-color: #dbe1ec;
            box-shadow: none;
        }

        .login-form .form-control:focus {
            border-color: #8ea3ce;
            box-shadow: 0 0 0 0.2rem rgba(40, 54, 95, 0.12);
        }

        .login-btn {
            min-height: 46px;
            border-radius: 0.8rem;
            background-color: #28365F;
            border-color: #28365F;
            color: #fff;
            font-weight: 700;
        }

        .login-btn:hover,
        .login-btn:focus {
            background-color: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .login-btn-secondary {
            min-height: 46px;
            border-radius: 0.8rem;
            font-weight: 700;
        }

        @media (max-width: 576px) {
            .login-shell {
                max-width: 420px;
                padding-top: 0.4rem;
            }

            .login-grid {
                gap: 0;
            }

            .login-panel {
                display: none;
            }

            .login-card-header {
                padding: 0.9rem 1rem;
            }

            .login-card-header h2 {
                font-size: 1rem;
                text-align: center;
            }

            .login-card-body {
                padding: 0.9rem;
            }

            .login-card-subtitle {
                margin-bottom: 0.85rem;
                font-size: 0.82rem;
                text-align: center;
            }

            .login-form .form-label {
                font-size: 0.9rem;
            }

            .login-form .form-control {
                min-height: 44px;
                font-size: 0.92rem;
            }

            .login-btn,
            .login-btn-secondary {
                min-height: 44px;
            }
        }

        @media (max-width: 991.98px) {
            .login-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid login-shell">
        <div class="login-grid">
            <section class="login-panel">
                <span class="login-chip">
                    <i class="bi bi-shield-lock-fill"></i>
                    Acesso de Gestao
                </span>
                <h1 class="login-title">Entrar como tecnico ou administrador</h1>
                <p class="login-text">
                    Use este acesso para cadastrar atletas, atualizar avaliacoes, acompanhar relatorios e administrar o
                    ambiente conforme o seu perfil.
                </p>

                <div class="login-lista">
                    <div class="login-lista-item">
                        <i class="bi bi-check2-circle"></i>
                        <span>Area unica para tecnico e administrador com o mesmo fluxo de autenticacao.</span>
                    </div>
                    <div class="login-lista-item">
                        <i class="bi bi-check2-circle"></i>
                        <span>Depois do login, o sistema redireciona para o painel correspondente ao seu perfil.</span>
                    </div>
                   
                </div>
            </section>

            <section class="login-card">
                <div class="login-card-header">
                    <h2>Entrar no sistema</h2>
                </div>

                <div class="login-card-body">
                    <p class="login-card-subtitle">Informe seu e-mail e senha para continuar.</p>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" class="login-form">
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
                            <a href="{{ route('public.home') }}" class="btn btn-secondary login-btn-secondary">
                                Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
