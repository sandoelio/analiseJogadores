@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <form action="{{ route('aluno.login.post') }}" method="POST"
        class="p-4 border rounded" style="max-width:400px;width:100%">
    @csrf
    <h4 class="mb-4 text-center">Login Atleta</h4>

    <div class="mb-3">
      <label for="email" class="form-label">E-mail da Instituição</label>
      <input name="email" id="email" type="email"
             value="{{ old('email') }}"
             class="form-control @error('email') is-invalid @enderror"
             required autofocus>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="mb-4">
      <label for="password" class="form-label">Senha</label>
      <input name="password" id="password" type="password"
             class="form-control @error('password') is-invalid @enderror"
             required>
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <div class="d-grid gap-2">
      <button class="btn btn-primary" type="submit">Entrar</button>
      <a href="{{ route('public.dashboard') }}" class="btn btn-secondary">Voltar</a>
    </div>
  </form>
</div>
@endsection
