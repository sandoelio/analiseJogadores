<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Analise de Desempenhos')</title>

    <link rel="icon" sizes="16x16" href="{{ asset('imagem/LOGO1.png') }}" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        html,
        body {
            min-height: 100vh;
            height: auto;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            overflow-y: auto;
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        header.site-header,
        footer.site-footer {
            flex-shrink: 0;
            background: #28365F;
            color: #ffffff;
            padding: 0.75rem 0;
            text-align: center;
        }

        .site-navbar {
            gap: 0.85rem;
            padding-top: 0;
            padding-bottom: 0;
        }

        .site-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            text-align: left;
        }

        .site-brand-logo {
            height: 42px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
        }

        .site-brand-title {
            margin: 0;
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .site-navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .site-navbar-btn {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.45rem 0.9rem;
            border-radius: 0.7rem;
            font-weight: 600;
        }

        .site-navbar-toggler {
            min-height: 36px;
            border-radius: 0.7rem;
            padding: 0.4rem 0.55rem;
        }

        .site-navbar-menu {
            top: 100%;
            margin-top: 0.45rem;
            z-index: 1000;
            background: #28365F;
            min-width: 230px;
            padding: 0.9rem;
            border-radius: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        }

        .site-navbar-user {
            display: block;
            margin-bottom: 0.75rem;
            color: #fff;
            font-size: 0.96rem;
            font-weight: 700;
            text-align: left;
        }

        footer.site-footer a {
            color: #ffd8a8;
            font-weight: bold;
        }

        main.site-main {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding: 0;
            margin: 0;
            min-height: 0;
        }

        .content-box {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            padding: 0;
            background: #FF7209;
            box-sizing: border-box;
            width: 100%;
            overflow: visible;
        }

        .flash-auto {
            transition: opacity 0.35s ease, transform 0.35s ease;
        }

        .flash-auto.flash-floating {
            position: fixed;
            top: 88px;
            right: 20px;
            z-index: 1085;
            width: min(520px, calc(100vw - 2rem));
            margin: 0;
            border-width: 1px;
            border-style: solid;
            box-shadow: 0 12px 28px rgba(17, 27, 51, 0.18);
            border-radius: 0.95rem;
        }

        .flash-auto.flash-floating.alert-success {
            background: linear-gradient(135deg, #f2fff7 0%, #dcfce7 100%);
            border-color: #86efac;
            color: #166534;
        }

        .flash-auto.flash-floating.alert-success .btn-close {
            filter: saturate(0.7);
        }

        .flash-auto.flash-floating.alert-warning {
            background: linear-gradient(135deg, #fff9eb 0%, #fef3c7 100%);
            border-color: #fcd34d;
            color: #92400e;
        }

        .flash-auto.flash-floating.alert-warning .btn-close {
            filter: saturate(0.7);
        }

        .flash-auto.flash-floating.alert-danger {
            background: linear-gradient(135deg, #fff2f2 0%, #fee2e2 100%);
            border-color: #fca5a5;
            color: #991b1b;
        }

        .flash-auto.flash-floating.alert-danger .btn-close {
            filter: saturate(0.7);
        }

        .flash-auto.flash-hiding {
            opacity: 0;
            transform: translateY(-8px);
            pointer-events: none;
        }

        @media (max-width: 576px) {
            header.site-header {
                padding: 0.65rem 0;
            }

            .content-box {
                padding: 1rem;
                -webkit-overflow-scrolling: touch;
            }

            .site-navbar {
                align-items: stretch !important;
            }

            .site-brand {
                justify-content: center;
                text-align: center;
            }

            .site-brand-logo {
                height: 38px;
            }

            .site-brand-title {
                font-size: 1.1rem;
            }

            .site-navbar-actions {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
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

            .flash-auto.flash-floating {
                top: 82px;
                left: 1rem;
                right: 1rem;
                width: auto;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <header class="site-header">
        <nav class="navbar container d-flex flex-column flex-md-row align-items-center justify-content-between navbar-dark site-navbar">
            <div class="site-brand mb-2 mb-md-0">
                <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" class="site-brand-logo"
                    loading="lazy">
                <h1 class="site-brand-title">Analises de desempenhos</h1>
            </div>

            <div class="site-navbar-actions">
                @if (auth('athlete')->check())
                    <form id="aluno-logout-form" action="{{ route('aluno.logout') }}" method="POST"
                        class="me-2 my-2 my-md-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light site-navbar-btn">
                            Sair
                        </button>
                    </form>
                @endif

                @auth
                    @php
                        $dashboardRoute = Auth::user()->is_admin ? 'admin.dashboard' : 'tecnico.dashboard';
                    @endphp
                    <a href="{{ route($dashboardRoute) }}"
                        class="btn btn-sm btn-outline-light me-2 my-2 my-md-0 site-navbar-btn"
                        style="background: transparent; border-color: #fff; color: #fff; z-index: 1040;">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                @endauth

                <div class="position-relative">
                    <button class="navbar-toggler btn btn-sm btn-light site-navbar-toggler" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarUser" aria-controls="navbarUser"
                        aria-expanded="false" aria-label="Toggle navigation" style="z-index: 1100;">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div id="navbarUser" class="collapse position-absolute end-0 site-navbar-menu">
                        @auth
                            <span class="site-navbar-user">Ola, {{ Auth::user()->name }}</span>
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
            </div>
        </nav>
    </header>

    @hasSection('hero')
        @yield('hero')
    @endif

    <main class="site-main">
        <div class="content-box">
            @yield('content')
        </div>
    </main>

    <footer class="site-footer">
        Copyright &copy; {{ date('Y') }} |
        <a href="https://instagram.com/piraja.basquete" target="_blank" rel="noopener noreferrer">Basquete Piraja</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.flash-auto[data-auto-dismiss]').forEach(alerta => {
                const tempo = Number(alerta.dataset.autoDismiss || 4500);

                window.setTimeout(() => {
                    try {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                            bootstrap.Alert.getOrCreateInstance(alerta).close();
                            return;
                        }
                    } catch (e) {}

                    alerta.classList.add('flash-hiding');

                    window.setTimeout(() => {
                        if (alerta.parentNode) {
                            alerta.parentNode.removeChild(alerta);
                        }
                    }, 350);
                }, tempo);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
