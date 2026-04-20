@extends('layouts.app')

@section('title', 'Relatorios dos Projetos')

@push('styles')
    <style>
        .materiais-modal-form {
            display: grid;
            gap: 0.9rem;
        }

        .materiais-modal-form .form-control,
        .materiais-modal-form textarea {
            border-radius: 0.8rem;
            border-color: #dbe1ec;
            box-shadow: none;
        }

        .materiais-modal-form .form-control:focus,
        .materiais-modal-form textarea:focus {
            border-color: #8ea3ce;
            box-shadow: 0 0 0 0.2rem rgba(40, 54, 95, 0.12);
        }

        .materiais-modal-alerta {
            margin-bottom: 1rem;
            border-radius: 0.9rem;
        }

        .materiais-modal-lista {
            display: grid;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .materiais-modal-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem;
            border: 1px solid #e0e6f0;
            border-radius: 0.95rem;
            background: #f8fafd;
        }

        .materiais-modal-item h3 {
            margin: 0;
            color: #1f2d4f;
            font-size: 0.96rem;
            font-weight: 700;
        }

        .materiais-modal-item p {
            margin: 0.25rem 0 0;
            color: #5f6b85;
            font-size: 0.84rem;
            line-height: 1.45;
        }

        .materiais-modal-meta {
            font-size: 0.8rem;
        }

        .materiais-modal-actions {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            flex-shrink: 0;
        }

        .materiais-modal-vazio {
            margin: 1rem 0 0;
            padding: 0.95rem;
            border: 1px dashed #ccd6e5;
            border-radius: 0.9rem;
            color: #5f6b85;
            background: #f8fafd;
        }

        .relatorios-shell {
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
            padding-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .relatorios-shell {
                display: flex;
                flex-direction: column;
                height: calc(100vh - 132px);
                padding-top: 1.2rem;
                padding-bottom: 0;
                overflow: hidden;
            }

            .relatorios-topo {
                flex: 0 0 auto;
                margin-bottom: 1rem;
            }

            .relatorios-card {
                flex: 1 1 auto;
                min-height: 0;
                display: flex;
                flex-direction: column;
            }

            .relatorios-card .tab-content {
                flex: 1 1 auto;
                min-height: 0;
                overflow: hidden;
            }

            .relatorios-card .tab-pane.active {
                display: flex;
                flex-direction: column;
                height: 100%;
            }
        }

        .relatorios-topo {
            display: grid;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .relatorios-topo-acoes {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.9rem 1rem;
            border: 1px solid #dbe1ec;
            border-radius: 0.8rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
        }

        .relatorios-topo-texto {
            margin: 0;
            color: #5f6b85;
            font-size: 0.86rem;
        }

        .relatorios-acoes-botoes {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .relatorios-pendencias-btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.6rem 1rem;
            border-radius: 0.85rem;
            background: #28365F;
            border: 1px solid #28365F;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .relatorios-pendencias-btn:hover {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .relatorios-subtitulo {
            margin: 0.35rem 0 0;
            color: #000;
            font-weight: 600;
        }

        .relatorios-voltar {
            margin-top: 0.5rem;
        }

        .relatorios-card {
            border: 1px solid #dbe1ec;
            border-radius: 0.8rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
            overflow: hidden;
        }

        .relatorios-card .nav-tabs {
            border-bottom: 1px solid #dbe1ec;
            background: #f7f9fc;
            padding: 0.75rem 0.75rem 0;
            gap: 0.4rem;
        }

        .relatorios-card .nav-link {
            border: 1px solid #dbe1ec;
            border-bottom: none;
            color: #2a3b5f;
            font-weight: 600;
            background: #edf2f8;
        }

        .relatorios-card .nav-link.active {
            color: #fff;
            background: #28365F;
            border-color: #28365F;
        }

        .relatorios-card .tab-content {
            padding: 1rem;
        }

        .relatorios-painel-titulo {
            margin: 0 0 0.85rem;
            color: #1f2d4f;
            font-size: 1rem;
            font-weight: 700;
        }

        .relatorios-painel-texto {
            margin: 0 0 0.9rem;
            color: #5f6b85;
            font-size: 0.86rem;
        }

        .relatorio-tabela-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .relatorio-tabela-wrap-scroll {
            max-height: 420px;
            overflow-y: auto;
        }

        @media (min-width: 992px) {
            .relatorio-tabela-wrap-scroll {
                flex: 1 1 auto;
                min-height: 0;
                max-height: none;
            }
        }

        .relatorio-tabela {
            width: 100%;
            min-width: 860px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .relatorio-tabela thead th {
            background: #f47a2a;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #d8dee9;
            padding: 0.7rem 0.8rem;
            text-align: center;
            white-space: nowrap;
        }

        .relatorio-tabela thead tr:first-child th {
            position: sticky;
            top: 0;
            z-index: 4;
        }

        .relatorio-tabela thead tr:nth-child(2) th {
            position: sticky;
            top: 43px;
            z-index: 3;
        }

        .relatorio-tabela tbody td,
        .relatorio-tabela tfoot td {
            background: #fff;
            color: #263248;
            border: 1px solid #d8dee9;
            padding: 0.7rem 0.8rem;
            text-align: center;
            min-width: 62px;
        }

        .relatorio-tabela .coluna-projeto {
            min-width: 220px;
            text-align: left;
            font-weight: 600;
            background: #1f2d4f;
            color: #fff;
        }

        .relatorio-tabela thead th:last-child,
        .relatorio-tabela tbody td:last-child,
        .relatorio-tabela tfoot td:last-child {
            min-width: 88px;
            font-weight: 700;
        }

        .relatorio-tabela tbody tr td:last-child,
        .relatorio-tabela tfoot td {
            background: #eef2f7;
            color: #1f2d4f;
        }

        .relatorio-tabela tfoot td {
            font-weight: 700;
        }

        .relatorio-vazio {
            margin: 0;
            color: #6a748a;
        }

        @media (max-width: 575.98px) {
            .relatorios-topo-acoes {
                flex-direction: column;
                align-items: stretch;
                padding: 0.75rem 0.8rem;
            }

            .relatorios-acoes-botoes {
                flex-direction: column;
                align-items: stretch;
            }

            .materiais-modal-item,
            .materiais-modal-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .relatorios-card .tab-content {
                padding: 0.8rem;
            }

            .relatorios-voltar {
                margin-top: 0;
            }

            .relatorio-tabela {
                min-width: 760px;
            }

            .relatorio-tabela thead th,
            .relatorio-tabela tbody td,
            .relatorio-tabela tfoot td {
                padding: 0.6rem 0.7rem;
                font-size: 0.88rem;
            }

            .relatorio-tabela .coluna-projeto {
                min-width: 180px;
            }

            .relatorio-tabela-wrap-scroll {
                max-height: 360px;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .relatorios-shell {
                max-width: 920px;
            }
        }

        @media (min-width: 1200px) {
            .relatorios-shell {
                max-width: 1220px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $camposMateriais = ['titulo', 'descricao', 'arquivo_pdf'];
        $abrirModalMateriais = session()->has('material_success') || collect($camposMateriais)->contains(fn($campo) => $errors->has($campo));
    @endphp

    <div class="relatorios-shell">
        <div class="relatorios-topo">
            <div class="relatorios-topo-acoes">
                <p class="relatorios-topo-texto">
                    Consulte os relatorios gerais por idade e sexo ou abra o painel de pendencias por instituicao.
                </p>

                <div class="relatorios-acoes-botoes">
                    <a href="{{ route('admin.relatorios.alertas') }}" class="relatorios-pendencias-btn">
                        <i class="bi bi-bell"></i>
                        Alertas administrativos
                    </a>

                    <button type="button" class="relatorios-pendencias-btn" data-bs-toggle="modal"
                        data-bs-target="#materiaisTecnicosModal">
                        <i class="bi bi-paperclip"></i>
                        Anexar arquivos
                    </button>

                    <a href="{{ route('admin.relatorios.comparativo') }}" class="relatorios-pendencias-btn">
                        <i class="bi bi-arrow-left-right"></i>
                        Comparar instituicoes
                    </a>

                    <a href="{{ route('admin.relatorios.pendencias') }}" class="relatorios-pendencias-btn">
                        <i class="bi bi-exclamation-diamond"></i>
                        Painel de pendencias
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary relatorios-voltar">
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        <div class="modal fade" id="materiaisTecnicosModal" tabindex="-1" aria-labelledby="materiaisTecnicosModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="materiaisTecnicosModalLabel">Anexar PDF para o modulo tecnico</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if (session('material_success'))
                            <div class="alert alert-success materiais-modal-alerta">
                                {{ session('material_success') }}
                            </div>
                        @endif

                        <form action="{{ route('materiais-tecnicos.store') }}" method="POST" enctype="multipart/form-data"
                            class="materiais-modal-form">
                            @csrf

                            <div>
                                <label for="titulo" class="form-label">Titulo do PDF</label>
                                <input type="text" id="titulo" name="titulo"
                                    class="form-control @error('titulo') is-invalid @enderror"
                                    value="{{ old('titulo') }}" placeholder="Ex.: Protocolo de avaliacao fisica" required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="descricao" class="form-label">Descricao</label>
                                <textarea id="descricao" name="descricao" rows="3"
                                    class="form-control @error('descricao') is-invalid @enderror"
                                    placeholder="Opcional: informe como o tecnico deve usar este arquivo.">{{ old('descricao') }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="arquivo_pdf" class="form-label">Arquivo PDF</label>
                                <input type="file" id="arquivo_pdf" name="arquivo_pdf" accept="application/pdf"
                                    class="form-control @error('arquivo_pdf') is-invalid @enderror" required>
                                <div class="form-text">Somente PDF, com tamanho maximo de 10 MB.</div>
                                @error('arquivo_pdf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="relatorios-pendencias-btn">
                                    <i class="bi bi-upload"></i>
                                    Salvar arquivo
                                </button>
                            </div>
                        </form>

                        <div class="materiais-modal-lista">
                            @forelse ($materiaisTecnicos as $material)
                                <article class="materiais-modal-item">
                                    <div>
                                        <h3>{{ $material->titulo }}</h3>
                                        <p class="materiais-modal-meta">
                                            Anexado em {{ $material->created_at?->format('d/m/Y H:i') ?? '--' }}
                                            @if ($material->arquivo_tamanho)
                                                • {{ number_format($material->arquivo_tamanho / 1048576, 2, ',', '.') }} MB
                                            @endif
                                            @if ($material->criador?->name)
                                                • por {{ $material->criador->name }}
                                            @endif
                                        </p>
                                        @if ($material->descricao)
                                            <p>{{ $material->descricao }}</p>
                                        @endif
                                    </div>

                                    <div class="materiais-modal-actions">
                                        <a href="{{ route('materiais-tecnicos.download', $material) }}"
                                            class="btn btn-outline-primary">
                                            Baixar PDF
                                        </a>

                                        <form action="{{ route('materiais-tecnicos.destroy', $material) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                Remover
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @empty
                                <p class="materiais-modal-vazio">
                                    Nenhum arquivo foi anexado ainda para o modulo tecnico.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relatorios-card">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#relatorio-masculino"
                        type="button" role="tab">
                        Masculino
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#relatorio-feminino"
                        type="button" role="tab">
                        Feminino
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="relatorio-masculino" role="tabpanel">
                    <h2 class="relatorios-painel-titulo">Atletas por projeto</h2>
                    <p class="relatorios-painel-texto">Linhas por projeto e colunas agrupadas por idade.</p>
                    @include('admin.relatorios.partials.tabela', [
                        'idades' => $idadesMasculino,
                        'relatorio' => $relatorioMasculino,
                    ])
                </div>

                <div class="tab-pane fade" id="relatorio-feminino" role="tabpanel">
                    <h2 class="relatorios-painel-titulo">Atletas por projeto</h2>
                    <p class="relatorios-painel-texto">Linhas por projeto e colunas agrupadas por idade.</p>
                    @include('admin.relatorios.partials.tabela', [
                        'idades' => $idadesFeminino,
                        'relatorio' => $relatorioFeminino,
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($abrirModalMateriais)
                const modalElement = document.getElementById('materiaisTecnicosModal');
                if (modalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            @endif
        });
    </script>
@endpush
