@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
  <div class="col-sm-10 col-md-8 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header text-center bg-primary text-white">
        <h2 class="h5 mb-0">Entrar no Sistema</h2>
      </div>
      <div class="card-body">
        @if(session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              class="form-control @error('email') is-invalid @enderror"
              required
              autofocus
            >
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
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

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Entrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
