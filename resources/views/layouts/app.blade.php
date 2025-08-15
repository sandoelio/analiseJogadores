<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Análises de Atletas')</title>

  <!-- Bootstrap CSS & Icons -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    rel="stylesheet"
  />

  <style>
    /* ===== Reset e Layout Flexível ===== */
    html,
    body {
      height: 100vh;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      overflow: auto; /* permite scroll quando o dropdown abre */
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    /* ===== Header e Footer ===== */
    header.site-header,
    footer.site-footer {
      flex-shrink: 0;
      background: #1B265E;
      color: #ffffff;
      padding: 1rem 0;
      text-align: center;
    }

    footer.site-footer a {
      color: #ffd8a8;
      font-weight: bold;
    }

    /* ===== Main e Content Box ===== */
    main.site-main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: stretch;
      padding: 0;
      margin: 0;
      min-height: 0; /* permite filhos encolherem corretamente */
    }

    .content-box {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      padding: 1.5rem;
      background: rgba(255, 159, 64, 0.8);
      box-sizing: border-box;
      width: 100%;
    }

    /* ===== Estilos Mobile ===== */
    @media (max-width: 576px) {
      .content-box {
        padding: 1rem;
        max-height: calc(100vh - 160px);
        -webkit-overflow-scrolling: touch;
      }

      footer.site-footer {
        padding: 1.5rem 1rem;
        font-size: 0.85rem;
      }

      .form-control {
        font-size: 0.9rem;
      }

      .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
      }
    }
  </style>

  @stack('styles')
</head>

<body>
  <!-- Header -->
  <header class="site-header">
    <nav
      class="navbar container d-flex align-items-center justify-content-between navbar-dark position-relative" style="background: #1B265E;"
    >
      <!-- Título com margem esquerda -->
      <h1 class="h4 text-white m-0 ms-3">Análises de Atletas</h1>

      @auth
        @php
          $dashboardRoute = Auth::user()->is_admin
            ? 'admin.dashboard'
            : 'aluno.dashboard';
        @endphp
        <a
          href="{{ route($dashboardRoute) }}"
          class="btn btn-sm btn-outline-light me-2"
        >
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      @endauth

      <div class="position-relative">
        <!-- Botão hamburger com margem direita -->
        <button
          class="navbar-toggler btn btn-sm btn-light me-3"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarUser"
          aria-controls="navbarUser"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Dropdown colapsável branco -->
        <div
          id="navbarUser"
          class="collapse position-absolute end-0 mt-2 p-3 rounded shadow me-3"
          style="background: #1B265E; z-index: 1000; min-width: 200px;"
        >
          @auth
            <span class="d-block mb-2">Olá, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
              @csrf
              <button type="submit" class="btn btn-sm btn-light w-100">
                Sair
              </button>
            </form>
          @endauth

          @guest
            <a href="{{ route('login') }}" class="btn btn-sm btn-light w-100">
              Login
            </a>
          @endguest
        </div>
      </div>
    </nav>
  </header>

  <!-- Hero (opcional) -->
  @hasSection('hero')
    @yield('hero')
  @endif

  <!-- Conteúdo centralizado -->
  <main class="site-main">
    <div class="content-box">
      @yield('content')
    </div>
  </main>

  <!-- Footer sempre visível -->
  <footer class="site-footer">
    Copyright &copy; {{ date('Y') }} |
    <a
      href="https://instagram.com/piraja.basquete"
      target="_blank"
      rel="noopener noreferrer"
      >Basquete Pirajá</a
    >
  </footer>

  <!-- Bootstrap JS & Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
  @stack('scripts')
</body>

</html>
