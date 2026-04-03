@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .edit-shell {
            max-width: 1040px;
            width: 100%;
            margin: 0 auto;
            padding: 1rem 0 1.15rem;
        }

        .edit-topo {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 0.95rem;
            margin-bottom: 0.95rem;
        }

        .edit-heading,
        .edit-voltar-wrap,
        .edit-card,
        .edit-resumo {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .edit-heading {
            flex: 1 1 auto;
            padding: 1rem 1.1rem;
        }

        .edit-voltar-wrap {
            flex: 0 0 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem;
        }

        .edit-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.6rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .edit-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.48rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .edit-text {
            margin: 0.38rem 0 0;
            color: #5f6b85;
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .edit-voltar {
            width: 100%;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 0.95rem;
            border-radius: 0.8rem;
            font-weight: 700;
        }

        .edit-grid {
            display: grid;
            grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.1fr);
            gap: 1rem;
            align-items: start;
        }

        .edit-resumo {
            padding: 1.05rem;
        }

        .edit-resumo-title {
            margin: 0 0 0.85rem;
            color: #1f2d4f;
            font-size: 1rem;
            font-weight: 700;
        }

        .edit-dados {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.7rem;
            margin-bottom: 0.9rem;
        }

        .edit-dado {
            padding: 0.85rem 0.9rem;
            border: 1px solid #e4eaf3;
            border-radius: 0.9rem;
            background: #f8fafc;
        }

        .edit-dado-label {
            display: block;
            margin-bottom: 0.2rem;
            color: #6a7690;
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .edit-dado-valor {
            color: #223154;
            font-size: 0.96rem;
            font-weight: 700;
            line-height: 1.35;
            word-break: break-word;
        }

        .edit-ajuda {
            display: grid;
            gap: 0.55rem;
        }

        .edit-ajuda-item {
            display: flex;
            align-items: flex-start;
            gap: 0.55rem;
            color: #44506b;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .edit-ajuda-item i {
            margin-top: 0.1rem;
            color: #28365F;
        }

        .edit-card-header {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 1rem 1.1rem 0.9rem;
            border-bottom: 1px solid #edf2f8;
        }

        .edit-card-header i {
            color: #28365F;
            font-size: 1.05rem;
        }

        .edit-card-header h2 {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.08rem;
            font-weight: 700;
        }

        .edit-card-body {
            padding: 1rem 1.1rem 1.1rem;
        }

        .edit-subtitle {
            margin: 0 0 1rem;
            color: #5f6b85;
            font-size: 0.9rem;
        }

        .edit-alerta {
            margin-bottom: 0.9rem;
            border-radius: 0.9rem;
        }

        .edit-form .form-label {
            color: #33405f;
            font-weight: 600;
        }

        .edit-form .form-control {
            min-height: 46px;
            border-radius: 0.8rem;
            border-color: #dbe1ec;
            box-shadow: none;
        }

        .edit-form .form-control:focus {
            border-color: #8ea3ce;
            box-shadow: 0 0 0 0.2rem rgba(40, 54, 95, 0.12);
        }

        .edit-btn {
            min-height: 46px;
            border-radius: 0.8rem;
            background: #28365F;
            border-color: #28365F;
            color: #fff;
            font-weight: 700;
        }

        .edit-btn:hover,
        .edit-btn:focus {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        @media (max-width: 575.98px) {
            .edit-shell {
                padding-top: 0.5rem;
            }

            .edit-dados {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 991.98px) {
            .edit-topo,
            .edit-grid {
                grid-template-columns: 1fr;
            }

            .edit-topo {
                display: grid;
            }

            .edit-voltar-wrap {
                flex-basis: auto;
            }
        }

        @media (max-width: 767.98px) {
            .edit-title {
                font-size: 1.22rem;
            }

            .edit-card-header {
                padding-bottom: 0.8rem;
            }

            .edit-resumo {
                padding: 0.95rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid edit-shell">
        <div class="edit-topo">
            <section class="edit-heading">
                <span class="edit-chip">
                    <i class="bi bi-pencil-square"></i>
                    Edicao
                </span>
                <h1 class="edit-title">Editar atleta</h1>
                <p class="edit-text">
                    Revise os dados basicos do cadastro e atualize apenas o nome do atleta sem alterar o fluxo atual da
                    listagem.
                </p>
            </section>

            <div class="edit-voltar-wrap">
                <a href="{{ route('aluno.index') }}" class="btn btn-outline-secondary edit-voltar">
                    <i class="bi bi-arrow-left me-1"></i>
                    Voltar
                </a>
            </div>
        </div>

        <div class="edit-grid">
            <section class="edit-resumo">
                <h2 class="edit-resumo-title">Atleta selecionado</h2>

                <div class="edit-dados">
                    <div class="edit-dado">
                        <span class="edit-dado-label">Nome atual</span>
                        <span class="edit-dado-valor">{{ $aluno->nome ?: '--' }}</span>
                    </div>

                    <div class="edit-dado">
                        <span class="edit-dado-label">Idade</span>
                        <span class="edit-dado-valor">
                            {{ $aluno->idade !== null ? $aluno->idade . ' anos' : 'Nao informada' }}
                        </span>
                    </div>

                    <div class="edit-dado">
                        <span class="edit-dado-label">Sexo</span>
                        <span class="edit-dado-valor">{{ $aluno->sexo ?: 'Nao informado' }}</span>
                    </div>

                    <div class="edit-dado">
                        <span class="edit-dado-label">Telefone</span>
                        <span class="edit-dado-valor">{{ $aluno->telefone ?: 'Nao informado' }}</span>
                    </div>
                </div>

                <div class="edit-ajuda">
                    <div class="edit-ajuda-item">
                        <i class="bi bi-check2-circle"></i>
                        <span>Use esta edicao para corrigir grafia, os demais dados do atleta seguem no normal.</span>
                    </div>
                </div>
            </section>

            <section class="edit-card">
                <div class="edit-card-header">
                    <i class="bi bi-person-lines-fill"></i>
                    <h2>Atualizacao do nome</h2>
                </div>

                <div class="edit-card-body">
                    <p class="edit-subtitle">Atualize o nome abaixo e confirme para salvar a alteracao.</p>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show edit-alerta flash-auto flash-floating"
                            data-auto-dismiss="4500" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('aluno.update', $aluno) }}" class="edit-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nome" class="form-label">Nome do atleta</label>
                            <input type="text" id="nome" name="nome"
                                class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome', $aluno->nome) }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn edit-btn w-100">
                            Atualizar
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
