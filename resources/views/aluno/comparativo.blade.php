{{-- resources/views/aluno/comparativo.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise de Desempenhos — ' . $aluno->nome)

@section('content')
<div class="row mb-4">
  <div class="col">
    <a href="{{ route('analise') }}" class="btn btn-sm btn-outline-secondary mb-3">
      &larr; Voltar
    </a>
    <h1 class="h4">Aluno: {{ $aluno->nome }}</h1>
    <p class="text-muted">Matrícula: {{ $aluno->matricula }}</p>
  </div>
</div>

@if(isset($mensagem))
  <div class="alert alert-info">
    {{ $mensagem }}
  </div>
@else
  <div class="row g-4">
    {{-- Análise Atual --}}
    <div class="col-12 col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          Análise Atual
          <span class="float-end">
            {{ $atual->created_at->format('d/m/Y') }}
          </span>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">Arremesso: {{ $atual->arremesso }}</li>
          <li class="list-group-item">Passe: {{ $atual->passe }}</li>
          <li class="list-group-item">Marcação: {{ $atual->marcacao }}</li>
          <li class="list-group-item">Bandeja: {{ $atual->bandeja }}</li>
          <li class="list-group-item">Rebote: {{ $atual->rebote }}</li>
        </ul>
      </div>
    </div>

    {{-- Análise Anterior --}}
    <div class="col-12 col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
          Análise Anterior
          <span class="float-end">
            {{ $anterior->created_at->format('d/m/Y') }}
          </span>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">Arremesso: {{ $anterior->arremesso }}</li>
          <li class="list-group-item">Passe: {{ $anterior->passe }}</li>
          <li class="list-group-item">Marcação: {{ $anterior->marcacao }}</li>
          <li class="list-group-item">Bandeja: {{ $anterior->bandeja }}</li>
          <li class="list-group-item">Rebote: {{ $anterior->rebote }}</li>
        </ul>
      </div>
    </div>
  </div>
@endif
@endsection
