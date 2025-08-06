{{-- resources/views/aluno/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Novo Aluno / Análise')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-6">

    <div class="card shadow-sm">
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

          <div class="mb-3">
            <label for="matricula" class="form-label">Matrícula</label>
            <input
              type="text"
              id="matricula"
              name="matricula"
              class="form-control @error('matricula') is-invalid @enderror"
              value="{{ old('matricula') }}"
              required
            >
            @error('matricula')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="arremesso" class="form-label">Arremesso</label>
            <input
              type="number"
              id="arremesso"
              name="arremesso"
              class="form-control @error('arremesso') is-invalid @enderror"
              value="{{ old('arremesso') }}"
              min="0"
              max="100"
              required
            >
            @error('arremesso')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="passe" class="form-label">Passe</label>
            <input
              type="number"
              id="passe"
              name="passe"
              class="form-control @error('passe') is-invalid @enderror"
              value="{{ old('passe') }}"
              min="0"
              max="100"
              required
            >
            @error('passe')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="marcacao" class="form-label">Marcação</label>
            <input
              type="number"
              id="marcacao"
              name="marcacao"
              class="form-control @error('marcacao') is-invalid @enderror"
              value="{{ old('marcacao') }}"
              min="0"
              max="100"
              required
            >
            @error('marcacao')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="finalizacao" class="form-label">Finalização</label>
            <input
              type="number"
              id="finalizacao"
              name="finalizacao"
              class="form-control @error('finalizacao') is-invalid @enderror"
              value="{{ old('finalizacao') }}"
              min="0"
              max="100"
              required
            >
            @error('finalizacao')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="jogada" class="form-label">Jogada</label>
            <input
              type="number"
              id="jogada"
              name="jogada"
              class="form-control @error('jogada') is-invalid @enderror"
              value="{{ old('jogada') }}"
              min="0"
              max="100"
              required
            >
            @error('jogada')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label for="dominio" class="form-label">Domínio de Bola</label>
            <input
              type="number"
              id="dominio"
              name="dominio"
              class="form-control @error('dominio') is-invalid @enderror"
              value="{{ old('dominio') }}"
              min="0"
              max="100"
              required
            >
            @error('dominio')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">
              Salvar
            </button>
            <a href="{{ route('aluno.create') }}" class="btn btn-secondary">
              Cancelar
            </a>
          </div>
        </form>

      </div>
    </div>

  </div>
</div>
@endsection
