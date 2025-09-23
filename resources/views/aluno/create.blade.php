@extends('layouts.app')

@section('title', 'Novo Aluno / Análise')

@push('styles')
  <style>
    input[readonly] {
      background-color: #e9ecef;
      opacity: 1;
      cursor: not-allowed;
    }

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

    html, body {
      overflow-x: hidden;
    }

    /* Layout horizontal fixo para perguntas e respostas */
    .saude-item {
      align-items: center;  
    }

    .saude-item label {
      margin-bottom: 0;
      font-weight: 500;
      flex: 1;
    }

    .saude-radios {
      display: flex;
      gap: 1rem;
      flex-shrink: 0;
    }
  </style>
@endpush

@section('content')
<div class="row justify-content-center mt-4 mb-4">
  <div class="col-12 col-md-10 col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-navbar-blue text-center">
        <h5 class="mb-0">Novo Atleta</h5>
      </div>

      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('aluno.store') }}" method="POST">
          @csrf

          {{-- Abas de navegação --}}
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#aba1">Identificação</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba2">Habilidades Técnicas</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba3">Atributos Físicos</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba4">Composição Corporal</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba5">Perguntas</a></li>
          </ul>

          {{-- Conteúdo das abas --}}
          <div class="tab-content mt-3">
            {{-- Aba 1: Nome --}}
            <div class="tab-pane fade show active" id="aba1">
              <div class="mb-3">
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
            </div>

            {{-- Aba 2: Habilidades Técnicas --}}
            <div class="tab-pane fade" id="aba2">
              <div class="row g-3">
                @foreach (['arremesso','passe','marcacao','bandeja','rebote','dominio'] as $campo)
                  <div class="col-6 col-md-6">
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
              </div>
            </div>

            {{-- Aba 3: Atributos Físicos --}}
            <div class="tab-pane fade" id="aba3">
              <div class="row g-3">
                @foreach ([
                  'envergadura' => 'Envergadura (cm)',
                  'velocidade' => 'Velocidade (s)',
                  'agilidade' => 'Agilidade (s)',
                  'salto_horizontal' => 'Salto Horizontal (cm)',
                  'resistencia' => 'Resistência (%)'
                ] as $campo => $label)
                  <div class="col-6 col-md-6">
                    <label for="{{ $campo }}" class="form-label">{{ $label }}</label>
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
              </div>
            </div>

            {{-- Aba 4: Composição Corporal --}}
            <div class="tab-pane fade" id="aba4">
              <div class="row g-3">
                @foreach ([
                  'massa_magra_kg' => 'Massa Magra (kg)',
                  'massa_adiposa_kg' => 'Massa Adiposa (kg)',
                  'massa_magra_pct' => 'Massa Magra (%)',
                  'massa_adiposa_pct' => 'Massa Adiposa (%)',
                  'peso_residual_kg' => 'Peso Residual (kg)'
                ] as $campo => $label)
                  <div class="col-6 col-md-6">
                    <label for="{{ $campo }}" class="form-label">{{ $label }}</label>
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
              </div>
            </div>

            {{-- Aba 5: Perguntas --}}
            <div class="tab-pane fade" id="aba5">
              <div class="row g-3">
                <div class="col-12 mt-2">
                  <h6 class="text-primary">Informações de Saúde</h6>
                </div>

                @php
                  $saudeCampos = [
                    'problema_saude'  => 'Possui problema de saúde?',
                    'atestado_valido' => 'Está com atestado válido?',
                    'usa_medicacao'   => 'Faz uso de medicação?'
                  ];
                @endphp

                @foreach ($saudeCampos as $campo => $label)
                  <div class="col-12 saude-item">
                    <label for="{{ $campo }}_sim">{{ $label }}</label>
                    <div class="saude-radios">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="{{ $campo }}" id="{{ $campo }}_sim" value="1" required>
                        <label class="form-check-label" for="{{ $campo }}_sim">Sim</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="{{ $campo }}" id="{{ $campo }}_nao" value="0">
                        <label class="form-check-label" for="{{ $campo }}_nao">Não</label>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Botões --}}
          <div class="mt-4 d-grid gap-2 d-md-flex justify-content-md-between">
            <button type="submit" class="btn btn-navbar-blue flex-md-grow-1">Salvar</button>
            <a href="{{ route('tecnico.dashboard') }}" class="btn btn-secondary flex-md-grow-1">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
