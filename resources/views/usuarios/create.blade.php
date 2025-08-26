{{-- resources/views/usuarios/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Cadastrar Usuário')

@push('styles')
    <style>
        /* ===== Botão personalizado ===== */
        .btn-navbar {
            background: #1B265E;
            color: #fff;
            border: 1px solid #1B265E;
        }
        .btn-navbar:hover {
            background: #162049;
            border-color: #162049;
        }

        /* ====================================================
           Em mobile, afasta o form do header e do footer,
           dá scroll interno e garante visibilidade total
        ====================================================== */
        @media (max-width: 576px) {
            .usuarios-create-wrapper {
                max-height: calc(100vh - 112px); /* subtrai header+footer */
                margin-top: 1px;    /* altura do header */
                margin-bottom: 56px; /* altura do footer */
                overflow-y: auto;
                padding: 0 1rem;
                box-sizing: border-box;
            }
        }
    </style>
@endpush

@section('content')
    <div class="usuarios-create-wrapper">
        <div class="row justify-content-center" style="margin-top:5%;">
            <div class="col-12 col-lg-8 col-xl-6">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">

                        {{-- Título --}}
                        <h2 class="card-title text-center mb-4" style="color: #1B265E;">
                            <i class="bi bi-person-plus-fill me-2"></i> Novo Usuário
                        </h2>

                        {{-- Mensagem de sucesso --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('usuarios.store') }}">
                            @csrf

                            <div class="row g-3">
                                {{-- Nome --}}
                                <div class="col-12 col-lg-6">
                                    <label for="name" class="form-label">Nome do usuario</label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control form-control-lg @error('name') is-invalid @enderror"
                                        placeholder="Nome e sobrenome"
                                        value="{{ old('name') }}"
                                        required
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Instituição --}}
                                <div class="col-12 col-lg-6">
                                    <label for="instituicao_nome" class="form-label">Instituição</label>
                                    <input
                                        type="text"
                                        id="instituicao_nome"
                                        name="instituicao_nome"
                                        class="form-control form-control-lg @error('instituicao_nome') is-invalid @enderror"
                                        placeholder="Nome da instituição"
                                        value="{{ old('instituicao_nome') }}"
                                        required
                                    >
                                    @error('instituicao_nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- E-mail --}}
                                <div class="col-12 col-lg-6">
                                    <label for="email" class="form-label">E-mail do usuario</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        placeholder="exemplo@dominio.com"
                                        value="{{ old('email') }}"
                                        required
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Senha --}}
                                <div class="col-12 col-lg-6">
                                    <label for="password" class="form-label">Senha do usuario</label>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="Mínimo 8 caracteres"
                                        required
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Confirmar Senha --}}
                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">Confirmar Senha do usuario</label>
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Repita a senha"
                                        required
                                    >
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- E-mail para atletas --}}
                                <div class="col-12 col-lg-6">
                                    <label for="athlete_email" class="form-label">E-mail para atletas</label>
                                    <input
                                        type="email"
                                        id="athlete_email"
                                        name="athlete_email"
                                        class="form-control form-control-lg @error('athlete_email') is-invalid @enderror"
                                        placeholder="exemplo@dominio.com"
                                        value="{{ old('athlete_email') }}"
                                        required
                                    >
                                    @error('athlete_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Senha para atletas --}}
                                <div class="col-12 col-lg-6">
                                    <label for="athlete_password" class="form-label">Senha para atletas</label>
                                    <input
                                        type="password"
                                        id="athlete_password"
                                        name="athlete_password"
                                        class="form-control form-control-lg @error('athlete_password') is-invalid @enderror"
                                        placeholder="Mínimo 8 caracteres"
                                        required
                                    >
                                    @error('athlete_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Botão Enviar --}}
                            <div class="mt-4 d-grid">
                                <button type="submit" class="btn btn-navbar btn-lg">
                                    Cadastrar
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
