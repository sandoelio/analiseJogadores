{{-- resources/views/usuarios/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .usuarios-create-shell {
            max-width: 1040px;
            margin: 0 auto;
            padding: 1rem 0 1.2rem;
        }

        .usuarios-create-topo {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .usuarios-create-heading,
        .usuarios-create-voltar-wrap,
        .usuarios-create-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .usuarios-create-heading {
            flex: 1 1 auto;
            padding: 1rem 1.1rem;
        }

        .usuarios-create-heading-top {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .usuarios-create-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .usuarios-create-texto-topo {
            margin: 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .usuarios-create-title {
            margin: 0.45rem 0 0;
            color: #1f2d4f;
            font-size: 1.46rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .usuarios-create-voltar-wrap {
            flex: 0 0 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem;
        }

        .usuarios-create-voltar {
            width: 100%;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .usuarios-create-card {
            overflow: hidden;
        }

        .usuarios-create-card .card-header {
            padding: 1rem 1.1rem;
            background: linear-gradient(135deg, #111b33 0%, #223156 100%) !important;
            color: #fff;
        }

        .usuarios-create-card .card-header h5 {
            margin: 0;
            font-size: 1.08rem;
            font-weight: 700;
        }

        .usuarios-create-card .card-body {
            padding: 1.15rem;
        }

        .usuarios-create-card .form-label {
            color: #33405f;
            font-weight: 600;
        }

        .usuarios-create-card .form-control {
            min-height: 48px;
            border-radius: 0.8rem;
            border-color: #dbe1ec;
            box-shadow: none;
        }

        .usuarios-create-card .form-control:focus {
            border-color: #8ea3ce;
            box-shadow: 0 0 0 0.2rem rgba(40, 54, 95, 0.12);
        }

        .usuarios-create-secao {
            margin-bottom: 1rem;
            padding: 0.95rem 1rem;
            border: 1px solid #e4eaf3;
            border-radius: 0.95rem;
            background: #fbfcfe;
        }

        .usuarios-create-secao-titulo {
            margin: 0 0 0.85rem;
            color: #1f2d4f;
            font-size: 0.98rem;
            font-weight: 700;
        }

        .usuarios-create-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            margin-top: 1.1rem;
        }

        .usuarios-create-actions .btn {
            min-height: 44px;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .btn-admin-primary {
            background: #111b33;
            border-color: #111b33;
            color: #fff;
        }

        .btn-admin-primary:hover,
        .btn-admin-primary:focus {
            background: #0b1428;
            border-color: #0b1428;
            color: #fff;
        }

        html,
        body {
            overflow-x: hidden;
        }

        @media (max-width: 576px) {
            .usuarios-create-shell {
                padding-top: 0.55rem;
            }

            .usuarios-create-topo {
                flex-direction: column;
                gap: 0.75rem;
            }

            .usuarios-create-voltar-wrap {
                flex-basis: auto;
                padding: 0.8rem;
            }

            .usuarios-create-title {
                font-size: 1.2rem;
            }

            .usuarios-create-texto-topo {
                display: none;
            }

            .usuarios-create-card .card-body {
                padding: 0.95rem;
            }

            .usuarios-create-secao {
                padding: 0.78rem;
                margin-bottom: 0.85rem;
            }

            .usuarios-create-secao-titulo {
                margin-bottom: 0.65rem;
                font-size: 0.94rem;
            }

            .usuarios-create-card .form-label {
                font-size: 0.9rem;
            }

            .usuarios-create-card .form-control {
                min-height: 44px;
                font-size: 0.92rem;
            }

            .usuarios-create-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endpush

@section('content')
    <div class="usuarios-create-shell">
        <div class="usuarios-create-topo">
            <div class="usuarios-create-heading">
                <div class="usuarios-create-heading-top">
                    <span class="usuarios-create-chip">
                        <i class="bi bi-person-plus-fill"></i>
                        Administracao
                    </span>
                    <p class="usuarios-create-texto-topo">
                        Cadastre o acesso do tecnico ou responsavel e defina tambem as credenciais do modulo atleta.
                    </p>
                </div>
            </div>
        </div>

        <div class="usuarios-create-card card">
            <div class="card-header text-center">
                <h5 class="mb-0">Cadastro de Usuario</h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('usuarios.store') }}">
                    @csrf

                    <div class="usuarios-create-secao">
                        <h2 class="usuarios-create-secao-titulo">Dados principais</h2>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="name" class="form-label">Nome do usuario</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Nome e sobrenome" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="instituicao_nome" class="form-label">Instituicao</label>
                                <input type="text" id="instituicao_nome" name="instituicao_nome"
                                    class="form-control @error('instituicao_nome') is-invalid @enderror"
                                    placeholder="Nome da instituicao" value="{{ old('instituicao_nome') }}" required>
                                @error('instituicao_nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="email" class="form-label">E-mail do usuario</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="exemplo@dominio.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="password" class="form-label">Senha do usuario</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimo 8 caracteres" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="password_confirmation" class="form-label">Confirmar senha</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Repita a senha" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="usuarios-create-secao">
                        <h2 class="usuarios-create-secao-titulo">Acesso do atleta</h2>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="athlete_email" class="form-label">E-mail para atletas</label>
                                <input type="email" id="athlete_email" name="athlete_email"
                                    class="form-control @error('athlete_email') is-invalid @enderror"
                                    placeholder="exemplo@dominio.com" value="{{ old('athlete_email') }}" required>
                                @error('athlete_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="athlete_password" class="form-label">Senha para atletas</label>
                                <input type="password" id="athlete_password" name="athlete_password"
                                    class="form-control @error('athlete_password') is-invalid @enderror"
                                    placeholder="Minimo 8 caracteres" required>
                                @error('athlete_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="usuarios-create-actions">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-admin-primary">
                            Cadastrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
