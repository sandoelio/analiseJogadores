{{-- resources/views/public/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Público')

@push('styles')
<style>
  /* 1) Sem scroll horizontal em toda a página */
  html, body {
    margin: 0;
    padding: 0;
    overflow-x: hidden !important;
  }

  /* 2) Container centralizado, sem overflow */
  .dashboard-container {
    min-height: calc(100vh - 14rem); /* header ativo */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  /* 3) Grid de botões fixa em 2 colunas, com gap */
  .dashboard-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
  }

  /* 4) Em mobile, muda para 1 coluna */
  @media (max-width: 576px) {
    .dashboard-buttons {
      grid-template-columns: 1fr;
    }
  }

  /* 5) Estilo unificado para logo e botões */
  .dashboard-buttons > * {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    background: #fff;
    border: 2px solid #ddd;
    border-radius: 0.5rem;
    text-decoration: none;
    color: #333;
    font-weight: 600;
    transition: background 0.2s, border-color 0.2s, color 0.2s;
  }

  /* 6) Hover idem para botões (logo não ficará “clicável”) */
  .dashboard-buttons a:hover {
    background: #f8f9fa;
    border-color: #28365F;
    color: #28365F;
  }

  .dashboard-buttons .logo-wrapper {
    background: #28365F;
    border-color: #28365F;
    color: #fff;  
  }

  /* 7) Logo com tamanho máximo e responsivo */
  .dashboard-buttons .logo-wrapper img {
    max-width: 150px;
    width: 100%;
    height: auto;

  }

  /* 8) Ícones dos botões */
  .dashboard-btn i {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
  }
</style>
@endpush

@section('content')
  <div class="container-fluid dashboard-container">
    <div class="dashboard-buttons">

      {{-- Logo no grid (sem hover/link) --}}
      <div class="logo-wrapper">
        <img 
          src="{{ asset('imagem/LOGO1.png') }}" 
          alt="Cesta Baiana" 
          loading="lazy"
        >
      </div>

      {{-- 1) Análise Individual --}}
      <a href="{{ route('analise.index') }}" class="dashboard-btn">
        <i class="bi bi-person-circle"></i>
        Análise Individual
      </a>

      {{-- 2) 1×1 Narrativo --}}
      <a href="{{ route('comparar.index') }}" class="dashboard-btn">
        <i class="bi bi-chat-dots-fill"></i>
        1×1 (Narração)
      </a>

      {{-- 3) 1×1 Gráfico --}}
      <a href="{{ route('comparar.grafico.index') }}" class="dashboard-btn">
        <i class="bi bi-bar-chart-line-fill"></i>
        1×1 (Gráfico)
      </a>

    </div>
  </div>
@endsection
