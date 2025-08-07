<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title', 'Análises de Atletas')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    /* ===== Reset e Layout Básico ===== */
    html, body {
      height: 100%;             /* altura total para o flex funcionar */
      margin: 0;                /* remove scroll vertical extra */
      padding: 0;
      overflow-x: hidden;       /* sem scroll horizontal */
      background: #F8F9FA;      /* cor de fundo clara */
    }

    body {
      display: flex;
      flex-direction: column;   /* empilha header → main → footer */
      font-family: 'Segoe UI', sans-serif;
    }

    /* ===== Caixa Centralizada ===== */
    .site-container {
      flex: 1;                  /* faz o main crescer para empurrar o footer */
      display: flex;
      justify-content: center;  /* centraliza horizontalmente */
      padding: 2rem 1rem;       /* espaçamento interno */
    }

    .content-box {
      width: 100%;
      max-width: 1140px;        /* largura máxima de página */
      background: rgba(255, 159, 64, 0.8);
      padding: 2rem;
      box-shadow: 0 2px 12px rgba(0,0,0,0.05);
      border-radius: 4px;
      overflow-x: hidden;
    }

    /* ===== Header ===== */
    header.site-header {
      background: #1B265E;
      color: #FFFFFF;
      padding: 1rem 0;
    }

    /* ===== Footer ===== */
    footer.site-footer {
      background: #1B265E;
      color: #FFFFFF;
      text-align: center;
      padding: 1rem 0;
      margin-top: auto;        /* gruda o footer ao fim do body flex */
    }
    footer.site-footer a {
      color: #FFD8A8;
      font-weight: bold;
    }

    /* Logo do banner */
    .logo-banner {
        margin-bottom: 0.50rem; /* ajuste conforme necessário */
    }
    
    /* ===== Remover transições de fade (se estiver vindo do Chart.js) ===== */
    .chartjs-render-monitor {
      transition: none !important;
    }

    .chart-wrapper {
        position: relative;
        width: 100%;
        height: 350px;
        overflow: auto;
    }

    @media (max-width: 576px) {
        #estatisticas-container {
            margin-left: -1rem;
            margin-right: -1rem;
        }
    }

  </style>

  @stack('styles')
</head>

<body>

  {{-- Header --}}
  <header class="site-header">
    <div class="container d-flex justify-content-between align-items-center">
      <h1 class="h4 m-0">Análises de Atletas</h1>
      <div class="d-flex align-items-center gap-3">
        @auth
          {{-- <a href="{{ route('analise.index') }}" class="btn btn-sm btn-outline-light">
            <i class="bi-bar-chart-fill"></i> Estatísticas
          </a> --}}
          <a href="{{ route('aluno.dashboard') }}" class="btn btn-sm btn-outline-light">
            <i class="bi-person-plus-fill"></i> Dashboard
          </a>
          <span>Olá, {{ Auth::user()->name }}</span>
          <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-light">Sair</button>
          </form>
        @endauth
        @guest
          <a href="{{ route('login') }}" class="btn btn-sm btn-light">Login</a>
        @endguest
      </div>
    </div>
  </header>

  {{-- Hero (opcional) --}}
  @hasSection('hero')
    @yield('hero')
  @endif

  {{-- Conteúdo centralizado --}}
  <div class="site-container">
    <div class="content-box">
      @yield('content')
    </div>
  </div>

  {{-- Footer sempre no final --}}
  <footer class="site-footer">
    Copyright &copy; {{ date('Y') }} | <a href="https://instagram.com/piraja.basquete" target="_blank" rel="noopener noreferrer">Basquete Pirajá</a>
  </footer>

  <!-- Bootstrap e Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
  @stack('scripts')
</body>
</html>