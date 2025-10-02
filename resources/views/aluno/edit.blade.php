@extends('layouts.app')

@section('title', 'Análise de Desempenhos')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-6 col-lg-5">

    <h2 class="mb-4">Editar Atleta</h2>
    <a href="{{ route('aluno.index') }}" class="btn btn-outline-secondary mb-3">
      ← Voltar
    </a>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('aluno.update', $aluno) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="nome" class="form-label">Nome do Atleta</label>
        <input
          type="text"
          id="nome"
          name="nome"
          class="form-control @error('nome') is-invalid @enderror"
          value="{{ old('nome', $aluno->nome) }}"
          required
        >
        @error('nome')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary w-100" style="background: #28365F;">
        Atualizar
      </button>
    </form>
  </div>
</div>
@endsection
