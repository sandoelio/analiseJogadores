@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-6 col-lg-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      {{-- Título --}}
      <h2 class="mb-0">Editar Usuário</h2>
      <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
        ← Voltar à Lista
      </a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
      @csrf
      @method('PUT')

      {{-- Nome --}}
      <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input
          type="text"
          id="name"
          name="name"
          class="form-control @error('name') is-invalid @enderror"
          value="{{ old('name', $usuario->name) }}"
          required
        >
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- E-mail do usuário --}}
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control @error('email') is-invalid @enderror"
          value="{{ old('email', $usuario->email) }}"
          required
        >
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Nova Senha do usuário --}}
      <div class="mb-3">
        <label for="password" class="form-label">Nova Senha (opcional)</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control @error('password') is-invalid @enderror"
          placeholder="Deixe em branco para não alterar"
        >
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Confirmar Senha do usuário --}}
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
        <input
          type="password"
          id="password_confirmation"
          name="password_confirmation"
          class="form-control"
          placeholder="Repita a nova senha"
        >
      </div>

      {{-- E-mail para atletas --}}
      <div class="mb-3">
        <label for="athlete_email" class="form-label">E-mail para atletas</label>
        <input
          type="email"
          id="athlete_email"
          name="athlete_email"
          class="form-control @error('athlete_email') is-invalid @enderror"
          value="{{ old('athlete_email', $usuario->instituicao->athlete_email) }}"
          required
        >
        @error('athlete_email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Senha para atletas --}}
      <div class="mb-3">
        <label for="athlete_password" class="form-label">Senha para atletas (opcional)</label>
        <input
          type="password"
          id="athlete_password"
          name="athlete_password"
          class="form-control @error('athlete_password') is-invalid @enderror"
          placeholder="Deixe em branco para não alterar"
        >
        @error('athlete_password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Botão Atualizar --}}
      <button type="submit" class="btn btn-primary w-100" style="background: #1B265E;">
        Atualizar
      </button>
    </form>
  </div>
</div>
@endsection
```