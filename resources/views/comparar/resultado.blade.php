@extends('layouts.app')

@section('title', 'Resultado do Duelo')

@section('content')
<div class="container my-4">

  {{-- Cabeçalho com placar --}}
  <div class="text-center mb-4">
    <h2 class="fw-bold">Duelo Mano a Mano</h2>
    <p class="fs-4">
      {{ $aluno1->nome }}
      <span class="badge bg-success">{{ $placar[$aluno1->nome] }}</span>
      ×
      <span class="badge bg-danger">{{ $placar[$aluno2->nome] }}</span>
      {{ $aluno2->nome }}
    </p>
  </div>

  {{-- Timeline de eventos --}}
  <div class="position-relative border-start border-3 border-secondary ps-4">
    @foreach($eventos as $idx => $linhas)
      <div class="mb-5 position-relative">
        {{-- Numeração do card --}}
        <div class="position-absolute top-0 start-0 translate-middle bg-primary text-white rounded-circle d-flex 
                    align-items-center justify-content-center" 
             style="width:2rem; height:2rem; margin-left:-1rem;">
          {{ $idx + 1 }}
        </div>
        <div class="bg-light p-3 rounded shadow-sm">
          <ul class="mb-0 ps-3">
            @foreach($linhas as $linha)
              <li>{!! nl2br(e($linha)) !!}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endforeach

    {{-- Se quiser garantir um “décimo card” de encerramento mesmo usando slice < total --}}
    @if(count($eventos) < ($loops = config('comparativo.eventos') + 2))
      <div class="mb-5 position-relative">
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
              {{ $aluno1->nome }}
              <span class="badge bg-success">{{ $placar[$aluno1->nome] }}</span>
              ×
              <span class="badge bg-danger">{{ $placar[$aluno2->nome] }}</span>
              {{ $aluno2->nome }}
            </li>
          </ul>
        </div>
      </div>
    @endif
  </div>

  {{-- Botão para gerar novo duelo (corrigido) --}}
  <div class="text-center mt-4">
    <a href="{{ route('comparar.index') }}"
       class="btn btn-lg"
       style="background: #1B265E; color: white;">
      <i class="bi bi-arrow-repeat me-1"></i> Gerar Novo Duelo
    </a>
  </div>

</div>
@endsection
