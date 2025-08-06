@extends('layouts.app')

@section('title', 'Usuários Cadastrados')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-10 col-lg-8">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="m-0">Usuários Cadastrados</h2>
      <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        + Novo Usuário
      </a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead class="table-light">
            <tr>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Instituição</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            @forelse($usuarios as $usuario)
              <tr>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ optional($usuario->instituicao)->nome ?? '—' }}</td>
                <td>
                  <a href="{{ route('usuarios.edit', $usuario) }}"
                     class="btn btn-sm btn-outline-secondary"
                     title="Editar">
                    <i class="bi bi-pencil"></i>
                  </a>

                  <form
                    action="{{ route('usuarios.destroy', $usuario) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Confirmar exclusão?');"
                  >
                    @csrf
                    @method('DELETE')
                    <button
                      type="submit"
                      class="btn btn-sm btn-outline-danger"
                      title="Excluir"
                    >
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">
                  Nenhum usuário encontrado.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
