@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-6 col-lg-5">

    <h2 class="mb-4">Editar Usuário</h2>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary mb-3">
      ← Voltar à Lista
    </a>

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

      {{-- E-mail --}}
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

      {{-- Senha --}}
      <div class="mb-3">
        <label for="password" class="form-label">Nova Senha (opcional)</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control @error('password') is-invalid @enderror"
        >
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Confirmar Senha --}}
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
        <input
          type="password"
          id="password_confirmation"
          name="password_confirmation"
          class="form-control"
        >
      </div>

      <button type="submit" class="btn btn-primary w-100">
        Atualizar
      </button>
    </form>
  </div>
</div>
@endsection
