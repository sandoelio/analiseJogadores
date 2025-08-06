<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sistema de Análise')</title>

  <!-- CSS do Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS do Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Fonte padrão -->
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
    }
  </style>
</head>
<body>
  <header class="bg-dark text-white py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
      <h1 class="h4 m-0">Sistema de Análise de Alunos</h1>

      <div class="d-flex align-items-center gap-3">
        {{-- exibido apenas para usuários autenticados --}}
        @auth
          <a href="{{ route('analise.index') }}" class="btn btn-sm btn-outline-light">
            <i class="bi-bar-chart-fill"></i> Estatísticas
          </a>

          <a href="{{ route('aluno.create') }}" class="btn btn-sm btn-outline-light">
            <i class="bi-person-plus-fill"></i> Novo Aluno
          </a>

          <span class="ms-2">Olá, {{ Auth::user()->name }}</span>

          <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-light">Sair</button>
          </form>
        @endauth

        {{-- exibido apenas para visitantes (não autenticados) --}}
        @guest
          <a href="{{ route('login') }}" class="btn btn-sm btn-light">
            Login
          </a>
        @endguest
      </div>
    </div>
  </header>

  <main class="container py-4">
    @yield('content')
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
