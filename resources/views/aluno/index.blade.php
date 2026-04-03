{{-- resources/views/aluno/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .alunos-shell {
            max-width: 1080px;
            margin: 0 auto;
            padding: 1rem 0 1.1rem;
        }

        .alunos-topo {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.9rem;
        }

        .alunos-heading,
        .alunos-resumo {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .alunos-heading {
            flex: 1 1 auto;
            padding: 1rem 1.1rem;
        }

        .alunos-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.55rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .alunos-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .alunos-text {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .alunos-resumo {
            flex: 0 0 220px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(135deg, #28365F 0%, #40548c 100%);
            color: #fff;
        }

        .alunos-resumo-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            opacity: 0.88;
        }

        .alunos-resumo-total {
            margin-top: 0.2rem;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .alunos-resumo-texto {
            margin-top: 0.45rem;
            font-size: 0.88rem;
            line-height: 1.4;
            opacity: 0.92;
        }

        .alunos-alerta {
            margin-bottom: 0.8rem;
            border-radius: 0.9rem;
        }

        .alunos-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 8px 20px rgba(26, 42, 80, 0.08);
            overflow: hidden;
        }

        .alunos-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.95rem 1.05rem;
            background: #fff;
            border-bottom: 1px solid #edf2f8;
        }

        .alunos-card-titulo {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.02rem;
            font-weight: 700;
        }

        .alunos-card-subtitulo {
            margin: 0.2rem 0 0;
            color: #5f6b85;
            font-size: 0.84rem;
        }

        .alunos-badge {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #f5f8fd;
            border: 1px solid #dbe1ec;
            color: #44506b;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .alunos-tabela-wrap {
            overflow-x: auto;
        }

        .alunos-tabela {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .alunos-tabela thead th {
            background: #f8fafc;
            color: #4c5975;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #edf2f8;
            white-space: nowrap;
        }

        .alunos-tabela tbody td {
            padding: 0.9rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f8;
        }

        .alunos-tabela tbody tr:last-child td {
            border-bottom: none;
        }

        .aluno-nome {
            color: #1f2d4f;
            font-weight: 700;
        }

        .aluno-idade {
            color: #5f6b85;
            font-weight: 600;
        }

        .alunos-acoes {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            border-radius: 0.8rem;
        }

        .action-btn i {
            font-size: 0.95rem;
        }

        .alunos-mobile {
            display: none;
            padding: 0.8rem;
        }

        .aluno-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            padding: 0.9rem;
            border: 1px solid #e4eaf3;
            border-radius: 0.95rem;
            background: #fbfcfe;
        }

        .aluno-item + .aluno-item {
            margin-top: 0.65rem;
        }

        .aluno-item-info {
            min-width: 0;
        }

        .aluno-item-info .aluno-nome {
            display: block;
            margin-bottom: 0.28rem;
        }

        .alunos-paginacao {
            padding: 0.9rem 1rem 1rem;
            border-top: 1px solid #edf2f8;
            background: #fff;
        }

        .alunos-vazio {
            padding: 1.8rem 1.2rem;
            border: 1px dashed #c9d5e7;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.9);
            text-align: center;
            color: #44506b;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.05);
        }

        .alunos-vazio i {
            display: block;
            margin-bottom: 0.75rem;
            color: #28365F;
            font-size: 1.8rem;
        }

        @media (max-width: 767.98px) {
            .alunos-shell {
                padding-top: 0.55rem;
            }

            .alunos-topo {
                flex-direction: column;
                gap: 0.7rem;
            }

            .alunos-resumo {
                flex-basis: auto;
                padding: 0.9rem 1rem;
            }

            .alunos-title {
                font-size: 1.2rem;
            }

            .alunos-text {
                font-size: 0.86rem;
            }

            .alunos-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.55rem;
            }

            .alunos-desktop {
                display: none;
            }

            .alunos-mobile {
                display: block;
            }

            .aluno-item {
                flex-direction: column;
                align-items: stretch;
            }

            .alunos-acoes {
                width: 100%;
                justify-content: flex-end;
            }

            .action-btn {
                width: 40px;
                height: 40px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid alunos-shell">
        <div class="alunos-topo">
            <div class="alunos-heading">
                <span class="alunos-chip">
                    <i class="bi bi-people-fill"></i>
                    Cadastro
                </span>
                <h1 class="alunos-title">Atletas cadastrados</h1>
                <p class="alunos-text">
                    Consulte os atletas da instituicao, revise os dados basicos e acesse rapidamente as acoes de editar
                    ou excluir dentro do fluxo atual.
                </p>
            </div>

            <div class="alunos-resumo">
                <span class="alunos-resumo-label">Total</span>
                <span class="alunos-resumo-total">{{ $totalAlunos }}</span>
                <span class="alunos-resumo-texto">Atletas disponiveis nesta instituicao.</span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alunos-alerta">
                {{ session('success') }}
            </div>
        @endif

        @if ($alunos->count())
            <div class="alunos-card">
                <div class="alunos-card-header">
                    <div>
                        <h2 class="alunos-card-titulo">Lista de atletas</h2>
                        <p class="alunos-card-subtitulo">Visualizacao organizada para desktop e mobile.</p>
                    </div>

                    <span class="alunos-badge">
                        {{ $alunos->firstItem() }}-{{ $alunos->lastItem() }} de {{ $alunos->total() }}
                    </span>
                </div>

                <div class="alunos-desktop">
                    <div class="alunos-tabela-wrap">
                        <table class="alunos-tabela">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th class="text-center">Idade</th>
                                    <th class="text-center">Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alunos as $aluno)
                                    <tr>
                                        <td>
                                            <span class="aluno-nome">{{ $aluno->nome }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="aluno-idade">
                                                {{ $aluno->idade !== null ? $aluno->idade . ' anos' : '--' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="alunos-acoes">
                                                <a href="{{ route('aluno.edit', $aluno) }}"
                                                    class="btn btn-outline-secondary action-btn" title="Editar">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('aluno.destroy', $aluno) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Deseja excluir este aluno?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger action-btn"
                                                        title="Excluir">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="alunos-mobile">
                    @foreach ($alunos as $aluno)
                        <div class="aluno-item">
                            <div class="aluno-item-info">
                                <span class="aluno-nome">{{ $aluno->nome }}</span>
                                <span class="aluno-idade">
                                    {{ $aluno->idade !== null ? $aluno->idade . ' anos' : '--' }}
                                </span>
                            </div>

                            <div class="alunos-acoes">
                                <a href="{{ route('aluno.edit', $aluno) }}" class="btn btn-outline-secondary action-btn"
                                    title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('aluno.destroy', $aluno) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Deseja excluir este aluno?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger action-btn" title="Excluir">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($alunos->hasPages())
                    <div class="alunos-paginacao">
                        <div class="d-flex justify-content-center">
                            {{ $alunos->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="alunos-vazio">
                <i class="bi bi-person-x-fill"></i>
                <strong>Nao ha atletas cadastrados ainda.</strong>
                <div class="mt-2">Quando novos atletas forem cadastrados, a listagem aparecera aqui.</div>
            </div>
        @endif
    </div>
@endsection
