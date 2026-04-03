@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .comparar-shell {
            max-width: 1020px;
            margin: 0 auto;
            padding: 1rem 0 1.15rem;
        }

        .comparar-topo {
            margin-bottom: 0.95rem;
        }

        .comparar-heading,
        .comparar-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .comparar-heading {
            padding: 1rem 1.1rem;
        }

        .comparar-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.55rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .comparar-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .comparar-text {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .comparar-alerta {
            margin-bottom: 0.8rem;
            border-radius: 0.9rem;
        }

        .comparar-card {
            overflow: hidden;
        }

        .comparar-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #edf2f8;
        }

        .comparar-card-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .comparar-card-subtitle {
            margin: 0.2rem 0 0;
            color: #5f6b85;
            font-size: 0.84rem;
        }

        .comparar-badge {
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

        .comparar-form-wrap {
            position: relative;
            padding: 1.05rem 1.1rem 1.1rem;
        }

        .comparar-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .comparar-campo {
            display: grid;
            gap: 0.45rem;
        }

        .comparar-label {
            color: #33405f;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .comparar-select {
            min-height: 48px;
            border-radius: 0.85rem;
            border-color: #dbe1ec;
            box-shadow: none;
        }

        .comparar-select:focus {
            border-color: #8ea3ce;
            box-shadow: 0 0 0 0.2rem rgba(40, 54, 95, 0.12);
        }

        .comparar-acoes {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .comparar-btn {
            min-height: 44px;
            padding: 0.6rem 1.2rem;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .comparar-btn-principal {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .comparar-btn-principal:hover,
        .comparar-btn-principal:focus {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .overlay-spinner {
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.82);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .overlay-spinner .spinner-border {
            width: 2.8rem;
            height: 2.8rem;
        }

        @media (max-width: 767.98px) {
            .comparar-shell {
                padding-top: 0.5rem;
            }

            .comparar-title {
                font-size: 1.16rem;
            }

            .comparar-text {
                display: none;
            }

            .comparar-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.45rem;
            }

            .comparar-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .comparar-form-wrap {
                padding: 0.9rem;
            }

            .comparar-card-title {
                font-size: 0.98rem;
            }

            .comparar-label {
                font-size: 0.8rem;
            }

            .comparar-select {
                min-height: 44px;
                font-size: 0.92rem;
            }

            .comparar-acoes {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
                margin-top: 0.85rem;
            }

            .comparar-btn {
                width: 100%;
                min-height: 42px;
                padding: 0.55rem 1rem;
            }

            .comparar-badge {
                min-height: 30px;
                padding: 0.28rem 0.65rem;
                font-size: 0.76rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $user = auth()->user();
        $isAdmin = auth()->check() && (int) ($user->is_admin ?? 0) === 1;
        $instituicaoId = $isAdmin
            ? null
            : (session('aluno_instituicao_id') ?? (auth()->check() ? ($user->instituicao_id ?? null) : null));
        $alunosInst = collect($instituicoes)->firstWhere('id', $instituicaoId)?->alunos ?? collect();
    @endphp

    <div class="container-fluid comparar-shell">
        <div class="comparar-topo">
            <div class="comparar-heading">
                <span class="comparar-chip">
                    <i class="bi bi-arrow-left-right"></i>
                    Comparar
                </span>
                <h1 class="comparar-title">Duelo entre atletas</h1>
                <p class="comparar-text">
                    @if ($isAdmin)
                        Selecione dois atletas para gerar a comparacao narrativa, inclusive entre instituicoes diferentes.
                    @else
                        Selecione dois atletas da instituicao disponivel no seu acesso para gerar a comparacao narrativa.
                    @endif
                </p>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-warning comparar-alerta text-center">
                {{ session('error') }}
            </div>
        @endif

        @if (!$isAdmin && !$instituicaoId)
            <div class="alert alert-danger comparar-alerta text-center">
                Nenhuma instituicao vinculada ao usuario.
            </div>
        @endif

        <div class="comparar-card">
            <div class="comparar-card-header">
                <div>
                    <h2 class="comparar-card-title">Selecao dos atletas</h2>
                </div>

                <span class="comparar-badge">
                    <i class="bi bi-lightning-charge-fill me-1"></i>
                    Comparativo narrado
                </span>
            </div>

            <div class="comparar-form-wrap">
                <div id="overlay-narracao" class="overlay-spinner d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>

                <form id="narracao-form" action="{{ route('comparar.narrar') }}" method="POST">
                    @csrf

                    <div class="comparar-grid">
                        <div class="comparar-campo">
                            <label for="aluno1_id" class="comparar-label">Primeiro atleta</label>
                            <select class="form-select comparar-select" id="aluno1_id" name="aluno1_id" required
                                @disabled(!$isAdmin && !$instituicaoId)>
                                <option value="">Selecione o primeiro atleta</option>

                                @if ($isAdmin)
                                    @foreach ($instituicoes as $inst)
                                        <optgroup label="{{ $inst->nome }}">
                                            @foreach ($inst->alunos as $aluno)
                                                <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @else
                                    @foreach ($alunosInst as $aluno)
                                        <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="comparar-campo">
                            <label for="aluno2_id" class="comparar-label">Segundo atleta</label>
                            <select class="form-select comparar-select" id="aluno2_id" name="aluno2_id" required disabled
                                @disabled(!$isAdmin && !$instituicaoId)>
                                <option value="">Selecione o segundo atleta</option>

                                @if ($isAdmin)
                                    @foreach ($instituicoes as $inst)
                                        <optgroup label="{{ $inst->nome }}">
                                            @foreach ($inst->alunos as $aluno)
                                                <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @else
                                    @foreach ($alunosInst as $aluno)
                                        <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="comparar-acoes">
                        <a href="{{ route('public.dashboard') }}" class="btn btn-outline-secondary comparar-btn">
                            <i class="bi bi-house-door me-1"></i>
                            Voltar
                        </a>

                        <button id="btn-narracao" type="submit"
                            class="btn comparar-btn comparar-btn-principal"
                            @disabled(!$isAdmin && !$instituicaoId)>
                            <span id="btn-text">Gerar narracao</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sel1 = document.getElementById('aluno1_id');
            const sel2 = document.getElementById('aluno2_id');
            const form = document.getElementById('narracao-form');
            const overlay = document.getElementById('overlay-narracao');
            const btn = document.getElementById('btn-narracao');
            const btnText = document.getElementById('btn-text');

            function atualizarOpcoes(origem, alvo) {
                const escolhido = origem.value;
                Array.from(alvo.options).forEach(opt => {
                    opt.disabled = opt.value !== '' && opt.value === escolhido;
                });
            }

            sel1.addEventListener('change', () => {
                if (!sel1.value) {
                    sel2.disabled = true;
                    sel2.value = '';
                    return;
                }

                sel2.disabled = false;
                sel2.value = '';
                atualizarOpcoes(sel1, sel2);
            });

            sel2.addEventListener('change', () => {
                atualizarOpcoes(sel2, sel1);
            });

            form.addEventListener('submit', () => {
                overlay.classList.remove('d-none');
                btn.disabled = true;
                btnText.textContent = 'Carregando...';
            });
        });
    </script>
@endpush
