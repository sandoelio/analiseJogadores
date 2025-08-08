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
        /* ===== Reset e Layout Flexível ===== */
        html,
        body {
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* elimina rolagem total */
            display: flex;
            flex-direction: column;
            background: #F8F9FA;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ===== Header e Footer fixos ===== */
        header.site-header,
        footer.site-footer {
            flex-shrink: 0;
            background: #1B265E;
            color: #FFFFFF;
            padding: 1rem 0;
            text-align: center;
        }

        footer.site-footer a {
            color: #FFD8A8;
            font-weight: bold;
        }

        /* ===== Conteúdo centralizado entre header e footer ===== */
        main.site-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            /* justify-content: center; */
            align-items: stretch;
            /* estica o content-box */
            padding: 0;
            margin: 0;
        }

        /* Estilo padrão para desktop */
        .content-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: 100%;
            padding: 1.5rem;
            /* pode ajustar conforme o visual desejado */
            background: rgba(255, 159, 64, 0.8);
            box-shadow: none;
            /* opcional: remove sombra se quiser colar mesmo */
            border-radius: 0;
            /* sem bordas arredondadas para colar nos cantos */
            -webkit-overflow-scrolling: touch;
            box-sizing: border-box;
            width: 100%;
        }



        /* Estilo específico para mobile */
        @media (max-width: 576px) {
            .content-box {
                overflow-y: auto;
                /* rolagem interna */
                padding: 1rem;
                max-height: calc(100vh - 160px);
                /* altura entre header e footer */
                -webkit-overflow-scrolling: touch;
                /* rolagem suave no iOS */
                box-sizing: border-box;
            }

            footer.site-footer {
                padding: 1.5rem 1rem;
                font-size: 0.85rem;
                text-align: center;
            }

            main.site-main {
                padding: 0;
                /* remove padding externo */
                align-items: stretch;
                /* garante altura total */
            }

            .form-control {
                font-size: 0.9rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }

            #estatisticas-container {
                margin-left: -1rem;
                margin-right: -1rem;
            }
        }


        /* Logo do banner */
        .logo-banner {
            margin-bottom: 0.50rem;
        }

        /* Chart.js */
        .chartjs-render-monitor {
            transition: none !important;
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            height: 350px;
            overflow: auto;
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
    <main class="site-main">
        <div class="content-box">
            @yield('content')
        </div>
    </main>

    {{-- Footer sempre visível --}}
    <footer class="site-footer">
        Copyright &copy; {{ date('Y') }} |
        <a href="https://instagram.com/piraja.basquete" target="_blank" rel="noopener noreferrer">Basquete Pirajá</a>
    </footer>

    <!-- Bootstrap e Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>

</html>
