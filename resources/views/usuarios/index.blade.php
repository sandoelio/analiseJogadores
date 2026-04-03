{{-- resources/views/usuarios/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .usuarios-shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1rem 0 1.2rem;
        }

        .usuarios-topo {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .usuarios-heading,
        .usuarios-voltar-wrap,
        .usuarios-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .usuarios-heading {
            flex: 1 1 auto;
            padding: 1rem 1.1rem;
        }

        .usuarios-heading-top {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .usuarios-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .usuarios-texto-topo {
            margin: 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .usuarios-title {
            margin: 0.45rem 0 0;
            color: #1f2d4f;
            font-size: 1.46rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .usuarios-voltar-wrap {
            flex: 0 0 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem;
        }

        .usuarios-voltar {
            width: 100%;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .usuarios-alerta {
            margin-bottom: 0.85rem;
            border-radius: 0.9rem;
        }

        .usuarios-card {
            overflow: hidden;
        }

        .usuarios-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.95rem 1.05rem;
            border-bottom: 1px solid #edf2f8;
        }

        .usuarios-card-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.04rem;
            font-weight: 700;
        }

        .usuarios-card-subtitle {
            margin: 0.18rem 0 0;
            color: #5f6b85;
            font-size: 0.84rem;
        }

        .usuarios-badge {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #f5f8fd;
            border: 1px solid #dbe1ec;
            color: #44506b;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .usuarios-tabela-wrap {
            overflow-x: auto;
        }

        .usuarios-lista-scroll.scroll-ativo {
            overflow-y: auto;
        }

        .usuarios-desktop .usuarios-lista-scroll.scroll-ativo {
            max-height: 320px;
        }

        .usuarios-desktop .usuarios-lista-scroll.scroll-ativo .usuarios-tabela thead th {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .usuarios-tabela {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .usuarios-tabela thead th {
            background: #f8fafc;
            color: #4c5975;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #edf2f8;
            white-space: nowrap;
        }

        .usuarios-tabela tbody td {
            padding: 0.9rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f8;
        }

        .usuarios-tabela tbody tr:last-child td {
            border-bottom: none;
        }

        .usuario-nome {
            color: #1f2d4f;
            font-weight: 700;
        }

        .usuario-email,
        .usuario-instituicao {
            color: #5f6b85;
        }

        .usuarios-acoes {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            border-radius: 0.8rem;
        }

        .usuarios-mobile {
            display: none;
            padding: 0.8rem;
        }

        .usuario-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            padding: 0.9rem;
            border: 1px solid #e4eaf3;
            border-radius: 0.95rem;
            background: #fbfcfe;
        }

        .usuario-item + .usuario-item {
            margin-top: 0.65rem;
        }

        .usuario-item-info {
            min-width: 0;
        }

        .usuario-item-info .usuario-nome {
            display: block;
            margin-bottom: 0.25rem;
        }

        .usuario-meta {
            display: block;
            color: #5f6b85;
            font-size: 0.9rem;
            word-break: break-word;
        }

        .usuarios-vazio {
            padding: 1.8rem 1.2rem;
            border: 1px dashed #c9d5e7;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.9);
            text-align: center;
            color: #44506b;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.05);
        }

        .usuarios-vazio i {
            display: block;
            margin-bottom: 0.75rem;
            color: #111b33;
            font-size: 1.8rem;
        }

        .usuarios-paginacao {
            padding: 0.95rem 1rem 1rem;
            border-top: 1px solid #edf2f8;
            background: #fff;
        }

        @media (max-width: 767.98px) {
            .usuarios-shell {
                padding-top: 0.55rem;
            }

            .usuarios-topo {
                flex-direction: column;
                gap: 0.75rem;
            }

            .usuarios-voltar-wrap {
                flex-basis: auto;
                padding: 0.6rem 0.75rem;
            }

            .usuarios-voltar {
                min-height: 40px;
                border-radius: 0.8rem;
            }

            .usuarios-title {
                font-size: 1.2rem;
            }

            .usuarios-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.55rem;
            }

            .usuarios-desktop {
                display: none;
            }

            .usuarios-mobile {
                display: block;
                padding: 0.7rem;
            }

            .usuarios-mobile.usuarios-lista-scroll.scroll-ativo {
                max-height: 360px;
            }

            .usuario-item {
                flex-direction: row;
                align-items: center;
                padding: 0.8rem;
                border-radius: 0.9rem;
                gap: 0.7rem;
            }

            .usuarios-acoes {
                width: auto;
                flex-shrink: 0;
                justify-content: flex-end;
                gap: 0.35rem;
            }

            .usuario-nome {
                margin-bottom: 0.15rem;
                font-size: 0.96rem;
            }

            .usuario-meta {
                font-size: 0.82rem;
                line-height: 1.35;
            }

            .action-btn {
                width: 36px;
                height: 36px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="usuarios-shell">
        <div class="usuarios-topo">
            <div class="usuarios-heading">
                <div class="usuarios-heading-top">
                    <span class="usuarios-chip">
                        <i class="bi bi-people-fill"></i>
                        Administracao
                    </span>
                    <p class="usuarios-texto-topo">
                        Consulte os usuarios cadastrados, revise os acessos e mantenha a estrutura administrativa do sistema.
                    </p>
                </div>
            </div>

            <div class="usuarios-voltar-wrap">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary usuarios-voltar">
                    Voltar
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show usuarios-alerta flash-auto flash-floating"
                data-auto-dismiss="4500" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($usuarios->count())
            <div class="usuarios-card">
                <div class="usuarios-card-header">
                    <div>
                        <h2 class="usuarios-card-title">Lista de usuarios</h2>
                    </div>

                    <span class="usuarios-badge">
                        {{ $usuarios->firstItem() }}-{{ $usuarios->lastItem() }} de {{ $usuarios->total() }}
                    </span>
                </div>

                <div class="usuarios-desktop">
                    <div class="usuarios-lista-scroll {{ $usuarios->count() > 3 ? 'scroll-ativo' : '' }}">
                    <div class="usuarios-tabela-wrap">
                        <table class="usuarios-tabela">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Instituicao</th>
                                    <th class="text-center">Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $usuario)
                                    <tr>
                                        <td>
                                            <span class="usuario-nome">{{ $usuario->name }}</span>
                                        </td>
                                        <td>
                                            <span class="usuario-email">{{ $usuario->email }}</span>
                                        </td>
                                        <td>
                                            <span class="usuario-instituicao">
                                                {{ optional($usuario->instituicao)->nome ?? 'Nao informada' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="usuarios-acoes">
                                                <a href="{{ route('usuarios.edit', $usuario) }}"
                                                    class="btn btn-outline-secondary action-btn" title="Editar">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Confirmar exclusao?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger action-btn" title="Excluir">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>

                <div class="usuarios-mobile usuarios-lista-scroll {{ $usuarios->count() > 3 ? 'scroll-ativo' : '' }}">
                    @foreach ($usuarios as $usuario)
                        <div class="usuario-item">
                            <div class="usuario-item-info">
                                <span class="usuario-nome">{{ $usuario->name }}</span>
                                <span class="usuario-meta">{{ $usuario->email }}</span>
                                <span class="usuario-meta">{{ optional($usuario->instituicao)->nome ?? 'Nao informada' }}</span>
                            </div>

                            <div class="usuarios-acoes">
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-outline-secondary action-btn"
                                    title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Confirmar exclusao?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger action-btn" title="Excluir">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($usuarios->hasPages())
                    <div class="usuarios-paginacao">
                        <div class="d-flex justify-content-center">
                            {{ $usuarios->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="usuarios-vazio">
                <i class="bi bi-person-x-fill"></i>
                <strong>Nenhum usuario encontrado.</strong>
                <div class="mt-2">Quando novos usuarios forem cadastrados, a listagem aparecera aqui.</div>
            </div>
        @endif
    </div>
@endsection
