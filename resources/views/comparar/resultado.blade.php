{{-- resources/views/comparar/resultado.blade.php --}}
@extends('layouts.app')

@section('title', 'Resultado do Duelo')

@section('content')
<div class="container my-4">

  {{-- Cabeçalho com placar --}}
  <div class="text-center mb-4">
    <h2 class="fw-bold">Vai começar o desafio !!!</h2>  
  </div>

  {{-- Timeline de eventos com margem inferior reduzida --}}
  <div class="position-relative border-start border-3 border-secondary ps-4 mb-3">
    @foreach($eventos as $idx => $linhas)
      <div class="mb-4 position-relative">
        {{-- Numeração do card --}}
        <div class="position-absolute top-0 start-0 translate-middle bg-primary text-white rounded-circle d-flex 
                    align-items-center justify-content-center" 
             style="width:2rem; height:2rem; margin-left:-1rem;">
          {{ $idx + 1 }}
        </div>
        <div class="bg-light p-3 rounded shadow-sm">
          <ul class="mb-0 ps-3">
            @foreach($linhas as $linha)
              <li>{!! nl2br($linha) !!}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endforeach

    {{-- Card de encerramento --}}
    @if(count($eventos) < config('comparativo.eventos') + 2)
      <div class="mb-4 position-relative">
        <div class="position-absolute top-0 start-0 translate-middle bg-dark text-white rounded-circle d-flex 
                    align-items-center justify-content-center"
             style="width:2rem; height:2rem; margin-left:-1rem;">
          {{ count($eventos) + 1 }}
        </div>
        <div class="bg-light p-3 rounded shadow-sm">
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
    @endif
  </div>

  {{-- Botões --}}
  <div class="d-flex justify-content-center gap-3 mb-2">
    <a href="{{ route('comparar.index') }}"
       class="btn btn-lg"
       style="background: #28365F; color: white;">
      <i class="bi bi-arrow-repeat me-1"></i> Gerar Novo Duelo
    </a>
    <a href="{{ route('public.dashboard') }}"
       class="btn btn-lg btn-secondary">
      Voltar
    </a>
  </div>
</div>
@endsection
