{{-- resources/views/aluno/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Alunos Cadastrados')

@push('styles')
    <style>
        /* Card Header com cor personalizada */
        .alunos-card .card-header {
            background-color: #1B265E !important;
        }

        /* Linhas alternadas mais suaves */
        .alunos-card .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(27, 38, 94, 0.05);
        }

        /* Centraliza verticalmente células e botões de ação */
        table thead th,
        table tbody td {
            vertical-align: middle !important;
        }

        /* Botões de ação compactos */
        .action-btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            padding: 0.25rem !important;
        }

        .action-btn i {
            font-size: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">

        {{-- Cartão de Listagem de Alunos --}}
        @if (!empty($alunos) && $alunos->count())
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card alunos-card shadow-sm">

                        <div class="card-header text-white">
                            <h5 class="mb-0">
                                Alunos Cadastrados ({{ $totalAlunos }})
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Nome</th>
                                            <th class="text-center">Matrícula</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alunos as $aluno)
                                            <tr>
                                                <td class="text-center">{{ $aluno->nome }}</td>
                                                <td class="text-center">{{ $aluno->matricula }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('aluno.edit', $aluno) }}"
                                                        class="btn btn-sm btn-outline-secondary action-btn" title="Editar">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>

                                                    <form action="{{ route('aluno.destroy', $aluno) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Deseja excluir este aluno?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger action-btn"
                                                            title="Excluir">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Footer de Paginação --}}
                        @if ($alunos->hasPages())
                            <div class="card-footer bg-white border-0">
                                <div class="d-flex justify-content-center">
                                    {{-- simples – só Anterior/Próximo --}}
                                    {{ $alunos->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="alert alert-info text-center mb-0">
                        Não há alunos cadastrados ainda.
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
