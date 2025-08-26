{{-- resources/views/aluno/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Novo Aluno / Análise')

@push('styles')
    <style>
      /* Readonly com aparência padronizada */
      input[readonly] {
        background-color: #e9ecef;
        opacity: 1;
        cursor: not-allowed;
      }

      /* Cores do header e botão */
      .bg-navbar-blue { background: #28365F !important; color: #fff; }
      .btn-navbar-blue {
        background: #28365F;
        border-color: #28365F;
        color: #fff;
      }
      .btn-navbar-blue:hover {
        background: #28365F;
        border-color: #28365F;
      }

      /* Card-body com scroll interno caso ultrapasse a área visível */
      @media (max-width: 576px) {
        .aluno-card-body {
          max-height: calc(100vh - 160px); /* header + footer + padding */
          overflow-y: auto;
          padding-right: 0.5rem; /* folga para a scrollbar interna */
        }
      }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center mt-4 mb-4">
      <div class="col-12 col-md-8 col-lg-6">

        <div class="card shadow-sm mb-4">
          <div class="card-header bg-navbar-blue text-center">
            <h5 class="mb-0">Novo Atleta</h5>
          </div>

          <div class="card-body aluno-card-body">
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('aluno.store') }}" method="POST">
              @csrf

              <div class="row g-3">
                {{-- Nome (100% largura) --}}
                <div class="col-12">
                  <label for="nome" class="form-label">Nome do Atleta</label>
                  <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Nome e sobrenome"
                    class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome') }}"
                    required
                  >
                  @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Estatísticas em 2 colunas --}}
                @foreach (['arremesso','passe','marcacao','bandeja','rebote','dominio'] as $campo)
                  <div class="col-6">
                    <label for="{{ $campo }}" class="form-label">
                      {{ ucfirst($campo==='dominio'?'Domínio de Bola':$campo) }}
                    </label>
                    <input type="hidden" name="{{ $campo }}" value="1">
                    <input
                      type="number"
                      id="{{ $campo }}"
                      class="form-control"
                      value="1"
                      min="0"
                      max="100"
                      readonly
                    >
                  </div>
                @endforeach

                {{-- Botões: em full-width no mobile, lado a lado no desktop --}}
                <div class="col-12">
                  <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <button type="submit" class="btn btn-navbar-blue flex-md-grow-1">
                      Salvar
                    </button>
                    <a href="{{ route('tecnico.dashboard') }}"
                       class="btn btn-secondary flex-md-grow-1">
                      Cancelar
                    </a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
@endsection
