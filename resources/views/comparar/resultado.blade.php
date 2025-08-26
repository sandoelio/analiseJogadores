{{-- resources/views/comparar/resultado.blade.php --}}
@extends('layouts.app')

@section('title', 'Resultado do Duelo')

@push('styles')
<style>
  /* Container dos cards: só scroll no mobile */
  .cards-wrapper {
    overflow-y: auto;
    padding-right: .5rem;  /* folga para scrollbar */
    padding-bottom: 60px;  /* folga para footer cobrir */
  }

  /* Faz o footer ficar sempre acima dos cards roláveis */
  footer {
    position: relative;
    z-index: 10;
  }

  /* Badges numeradas */
  .card-number {
    position: absolute;
    top: .5rem;
    left: .5rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #0d6efd;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    z-index: 1;
  }
  .card-number.final {
    background: #212529;
  }

  /* Cards de evento */
  .evento-card {
    position: relative;
    padding-top: 1.5rem;    /* espaço para a badge */
  }
  .evento-card .card-body {
    padding-top: .5rem;
    max-height: 180px;
    overflow-y: auto;
    font-size: .9rem;
    line-height: 1.3;
  }

  /* Tweak fino para mobile <576px */
  @media (max-width: 575.98px) {
    .cards-wrapper {
      height: calc(65vh - 100px); /* ajuste conforme header/mobile-footer */
    }
    .evento-card .card-body {
      padding: .75rem;
      max-height: 150px;
    }
  }
</style>
@endpush

@section('content')
<div class="container">
  {{-- Cabeçalho --}}
  <div class="row justify-content-center mb-4">
    <div class="col-12 col-md-8 text-center">
      <h4 class="fw-bold">Vai começar o desafio !!!</h4>
    </div>
  </div>

  {{-- Wrapper que rola só no mobile; no desktop expande normalmente --}}
  <div class="cards-wrapper">
    <div class="row g-3">
      @php
        $eventCount = count($eventos);
        $showFinal  = $eventCount < config('comparativo.eventos') + 2;
      @endphp

      @foreach($eventos as $idx => $linhas)
        <div class="col-12 col-sm-6 col-md-4">
          <div class="card evento-card shadow-sm">
            <div class="card-number">{{ $idx + 1 }}</div>
            <div class="card-body">
              <ul class="mb-0 ps-3">
                @foreach($linhas as $linha)
                  <li>{!! nl2br($linha) !!}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endforeach

      @if($showFinal)
        <div class="col-12 col-sm-6 col-md-4">
          <div class="card evento-card shadow-sm">
            <div class="card-number final">{{ $eventCount + 1 }}</div>
            <div class="card-body">
              <ul class="mb-0 ps-3">
                <li><strong>⏱️ Fim de partida</strong></li>
                <li>
                  Placar final:
                  <strong>{{ $aluno1->nome }}</strong>
                  <span class="badge bg-success">{{ $placar[$aluno1->nome] }}</span>
                  ×
                  <span class="badge bg-danger">{{ $placar[$aluno2->nome] }}</span>
                  <strong>{{ $aluno2->nome }}</strong>
                </li>
              </ul>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>

  {{-- Botões --}}
  <div class="row justify-content-center mt-4 mb-2">
    <div class="col-auto">
      <a href="{{ route('comparar.index') }}"
         class="btn btn-lg text-white"
         style="background: #28365F;">
        <i class="bi bi-arrow-repeat me-1"></i> Gerar Novo Duelo
      </a>
    </div>
    <div class="col-auto">
      <a href="{{ route('public.dashboard') }}"
         class="btn btn-lg btn-secondary">
        Voltar
      </a>
    </div>
  </div>
</div>
@endsection
