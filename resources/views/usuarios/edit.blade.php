{{-- resources/views/usuarios/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .usuarios-edit-shell {
            max-width: 1040px;
            margin: 0 auto;
            padding: 1rem 0 1.2rem;
        }

        .usuarios-edit-heading,
        .usuarios-edit-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .usuarios-edit-heading {
            flex: 1 1 auto;
            padding: 1rem 1.1rem;
        }

        .usuarios-edit-heading-top {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .usuarios-edit-chip {
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

        .usuarios-edit-texto-topo {
            margin: 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .usuarios-edit-card {
            overflow: hidden;
        }

        .usuarios-edit-card .card-header {
            padding: 1rem 1.1rem;
            background: linear-gradient(135deg, #111b33 0%, #223156 100%) !important;
            color: #fff;
        }

        .usuarios-edit-card .card-header h5 {
            margin: 0;
            font-size: 1.08rem;
            font-weight: 700;
        }

        .usuarios-edit-card .card-body {
            padding: 1.15rem;
        }

        .usuarios-edit-card .form-label {
            color: #33405f;
            font-weight: 600;
        }

        .usuarios-edit-card .form-control {
            min-height: 48px;
            border-radius: 0.8rem;
            border-color: #dbe1ec;
            box-shadow: none;
        }

        .usuarios-edit-card .form-control:focus {
            border-color: #8ea3ce;
            box-shadow: 0 0 0 0.2rem rgba(40, 54, 95, 0.12);
        }

        .usuarios-edit-secao {
            margin-bottom: 1rem;
            padding: 0.95rem 1rem;
            border: 1px solid #e4eaf3;
            border-radius: 0.95rem;
            background: #fbfcfe;
        }

        .usuarios-edit-secao-titulo {
            margin: 0 0 0.85rem;
            color: #1f2d4f;
            font-size: 0.98rem;
            font-weight: 700;
        }

        .usuarios-edit-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            margin-top: 1.1rem;
        }

        .usuarios-edit-actions .btn {
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
            .usuarios-edit-shell {
                padding-top: 0.55rem;
            }

            .usuarios-edit-texto-topo {
                display: none;
            }

            .usuarios-edit-card .card-body {
                padding: 0.95rem;
            }

            .usuarios-edit-secao {
                padding: 0.78rem;
                margin-bottom: 0.85rem;
            }

            .usuarios-edit-secao-titulo {
                margin-bottom: 0.65rem;
                font-size: 0.94rem;
            }

            .usuarios-edit-card .form-label {
                font-size: 0.9rem;
            }

            .usuarios-edit-card .form-control {
                min-height: 44px;
                font-size: 0.92rem;
            }

            .usuarios-edit-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
@endpush

@section('content')
    <div class="usuarios-edit-shell">
        <div class="usuarios-edit-heading mb-3">
            <div class="usuarios-edit-heading-top">
                <span class="usuarios-edit-chip">
                    <i class="bi bi-pencil-square"></i>
                    Administracao
                </span>
                <p class="usuarios-edit-texto-topo">
                    Atualize os dados do usuario e, se necessario, altere tambem as credenciais do modulo atleta.
                </p>
            </div>
        </div>

        <div class="usuarios-edit-card card">
            <div class="card-header text-center">
                <h5 class="mb-0">Atualizacao de Usuario</h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                    @csrf
                    @method('PUT')

                    <div class="usuarios-edit-secao">
                        <h2 class="usuarios-edit-secao-titulo">Dados principais</h2>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="name" class="form-label">Nome do usuario</label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $usuario->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="email" class="form-label">E-mail do usuario</label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $usuario->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="password" class="form-label">Nova senha</label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Deixe em branco para nao alterar">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="password_confirmation" class="form-label">Confirmar senha</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Repita a nova senha">
                            </div>
                        </div>
                    </div>

                    <div class="usuarios-edit-secao">
                        <h2 class="usuarios-edit-secao-titulo">Acesso do atleta</h2>
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="athlete_email" class="form-label">E-mail para atletas</label>
                                <input type="email" id="athlete_email" name="athlete_email"
                                    class="form-control @error('athlete_email') is-invalid @enderror"
                                    value="{{ old('athlete_email', $usuario->instituicao->athlete_email) }}" required>
                                @error('athlete_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-6">
                                <label for="athlete_password" class="form-label">Senha para atletas</label>
                                <input type="password" id="athlete_password" name="athlete_password"
                                    class="form-control @error('athlete_password') is-invalid @enderror"
                                    placeholder="Deixe em branco para nao alterar">
                                @error('athlete_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="usuarios-edit-actions">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-admin-primary">
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
