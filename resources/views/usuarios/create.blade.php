{{-- resources/views/usuarios/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Cadastrar Usuário')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-6 col-lg-5">
    <h2 class="mb-4">+ Novo Usuário</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-4">
  ← Voltar
</a>

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('usuarios.store') }}">
      @csrf

      {{-- Nome --}}
      <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input
          type="text"
          id="name"
          name="name"
          class="form-control @error('name') is-invalid @enderror"
          value="{{ old('name') }}"
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
          value="{{ old('email') }}"
          required
        >
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Senha --}}
      <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control @error('password') is-invalid @enderror"
          required
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
          required
        >
      </div>

      {{-- Campo para nova instituição --}}
      <div class="mb-3">
        <label for="instituicao_nome" class="form-label">Instituição</label>
        <input
          type="text"
          id="instituicao_nome"
          name="instituicao_nome"
          class="form-control @error('instituicao_nome') is-invalid @enderror"
          value="{{ old('instituicao_nome') }}"
          required
        >
        @error('instituicao_nome')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Botão de envio --}}
      <button type="submit" class="btn btn-primary w-100">
        Cadastrar
      </button>
    </form>
  </div>
</div>
@endsection
