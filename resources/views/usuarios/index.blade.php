{{-- resources/views/usuarios/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Usuários Cadastrados')

@push('styles')
<style>
  /* Tabela compacta para desktop */
  .table-sm td, .table-sm th {
    padding: .3rem .5rem;
  }
  .action-btns .btn {
    padding: .25rem .5rem;
  }

  /* Estilo dos cards no mobile */
  .user-card {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: .5rem;
    padding: .75rem;
    margin-bottom: .75rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }
  .user-card .card-header-mobile {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: .5rem;
  }
  .user-card h5 {
    margin: 0;
    font-size: 1rem;
    word-break: break-word;
  }
  .user-card .meta {
    font-size: .875rem;
    color: #555;
    word-break: break-word;
    margin-bottom: .25rem;
  }
  .user-card .action-btns {
    display: flex;
    gap: .5rem;
  }

  /* ============================= *
     Ajustes só para desktop (md+)
  * ============================= */
  @media (min-width: 768px) {
    /* impede qualquer scroll horizontal na página */
    html, body {
      overflow-x: hidden;
    }

    /* remove scroll interno da tabela no desktop */
    .table-responsive {
      overflow-x: hidden;
    }

    /* espaço entre o nav e o card de listagem */
    .d-none.d-md-block.card.shadow-sm {
      margin-top: 2rem;
    }
  }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-10 col-lg-8">

    {{-- Mensagem de sucesso --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabela para desktop (md+) --}}
    <div class="d-none d-md-block card shadow-sm">
      <div class="table-responsive">
        <table class="table table-striped table-sm mb-0">
          <thead class="table-light">
            <tr>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Instituição</th>
              <th class="text-center">Ações</th>
            </tr>
          </thead>
          <tbody>
            @forelse($usuarios as $usuario)
              <tr>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ optional($usuario->instituicao)->nome ?? '—' }}</td>
                <td class="text-center action-btns">
                  <a href="{{ route('usuarios.edit', $usuario) }}"
                     class="btn btn-outline-secondary btn-sm"
                     title="Editar">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('usuarios.destroy', $usuario) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Confirmar exclusão?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" title="Excluir">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center py-4">Nenhum usuário encontrado.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Lista de cards para mobile (sm-) --}}
    <div class="d-block d-md-none">
      @forelse($usuarios as $usuario)
        <div class="user-card">
          <div class="card-header-mobile">
            <h5>{{ $usuario->name }}</h5>
            <div class="action-btns">
              <a href="{{ route('usuarios.edit', $usuario) }}"
                 class="btn btn-outline-secondary btn-sm"
                 title="Editar">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('usuarios.destroy', $usuario) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Confirmar exclusão?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger btn-sm" title="Excluir">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </div>
          <div class="meta">{{ $usuario->email }}</div>
          <div class="meta">{{ optional($usuario->instituicao)->nome ?? '—' }}</div>
        </div>
      @empty
        <p class="text-center text-muted">Nenhum usuário encontrado.</p>
      @endforelse
    </div>

    {{-- Footer de Paginação --}}
    @if ($usuarios->hasPages())
      <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-center">
          {{ $usuarios->links('pagination::bootstrap-5') }}
        </div>
      </div>
    @endif

  </div>
</div>
@endsection
