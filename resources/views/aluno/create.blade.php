{{-- resources/views/aluno/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Novo Aluno / Análise')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        {{-- Cartão de Formulário --}}
        <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white text-center">
            <h5 class="mb-0">Novo Aluno / Análise</h5>
        </div>
        <div class="card-body">

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('aluno.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Aluno</label>
                    <input
                    type="text"
                    id="nome"
                    name="nome"
                    class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome') }}"
                    required
                    >
                    @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Demais campos de análise --}}
                @foreach(['arremesso','passe','marcacao','finalizacao','jogada','dominio'] as $campo)
                    <div class="mb-3">
                    <label for="{{ $campo }}" class="form-label">
                        {{ ucfirst($campo === 'dominio' ? 'Domínio de Bola' : $campo) }}
                    </label>
                    <input
                        type="number"
                        id="{{ $campo }}"
                        name="{{ $campo }}"
                        class="form-control @error($campo) is-invalid @enderror"
                        value="{{ old($campo) }}"
                        min="0"
                        max="100"
                        required
                    >
                    @error($campo)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    </div>
                @endforeach

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <a href="{{ route('aluno.create') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

        </div>
        </div>

        {{-- Cartão de Listagem de Alunos --}}
        @if(!empty($alunos) && $alunos->count())
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Alunos Cadastrados ({{ $alunos->count() }})</h5>
            </div>
            <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped mb-0">
                <thead class="table-light">
                    <tr>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Matrícula</th>
                    <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alunos as $aluno)
                    <tr>
                        <td class="text-center">{{ $aluno->nome }}</td>
                        <td class="text-center">{{ $aluno->matricula }}</td>
                        <td class="d-flex justify-content-center gap-2">
                        <a
                            href="{{ route('aluno.edit', $aluno) }}"
                            class="btn btn-sm btn-outline-secondary action-btn"
                            title="Editar"
                        >
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form
                            action="{{ route('aluno.destroy', $aluno) }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Deseja excluir este aluno?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                            type="submit"
                            class="btn btn-sm btn-outline-danger action-btn"
                            title="Excluir"
                            >
                            <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@section('styles')
<style>
  /* padroniza tamanho e alinhamento dos botões */
  .action-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    padding: 0.25rem !important;
  }

  /* tamanho do ícone */
  .action-btn i {
    font-size: 1rem;
    line-height: 1;
  }

  /* garante que todo conteúdo de célula fique verticalmente centralizado */
  table tbody tr td {
    vertical-align: middle !important;
  }
</style>
@endsection
