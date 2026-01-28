{{-- resources/views/comparativo/narracao.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise de Desempenhos')

@push('styles')
    <style>
        .back-logonarracao {
            background: #28365F;
            display: block;
            margin: 0 auto 0.5rem;
            max-width: 200px;
            width: 100%;
            height: auto;
        }

        .form-select {
            display: block;
            margin: 0 auto;
            max-width: 300px;
            width: 100%;
        }

        .overlay-spinner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .overlay-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .form-wrapper { position: relative; }
    </style>
@endpush

@section('content')
    @php
        $user = auth()->user();
        $isAdmin = auth()->check() && (int) ($user->is_admin ?? 0) === 1;

        // NÃO-admin:
        // atleta -> sessão
        // técnico -> user->instituicao_id
        $instituicaoId = $isAdmin
            ? null
            : (session('aluno_instituicao_id') ?? (auth()->check() ? ($user->instituicao_id ?? null) : null));

        // alunos apenas da instituição (para atleta/técnico)
        $alunosInst = collect($instituicoes)
            ->firstWhere('id', $instituicaoId)
            ?->alunos ?? collect();
    @endphp

    <div class="container my-2">

        {{-- Mensagem de erro --}}
        @if (session('error'))
            <div class="alert alert-warning text-center">{{ session('error') }}</div>
        @endif

        {{-- Aviso se técnico/atleta não tiver instituição vinculada --}}
        @if (!$isAdmin && !$instituicaoId)
            <div class="alert alert-danger text-center">
                Nenhuma instituição vinculada ao usuário.
            </div>
        @endif

        <div class="form-wrapper mt-2">
            <div id="overlay-narracao" class="overlay-spinner d-none">
                <div class="spinner-border text-primary" role="status"></div>
            </div>

            <form id="narracao-form" action="{{ route('comparar.narrar') }}" method="POST">
                @csrf

                {{-- Atleta 1 --}}
                <div class="mb-3">
                    <select class="form-select" id="aluno1_id" name="aluno1_id" required
                        @disabled(!$isAdmin && !$instituicaoId)>
                        <option value="">Selecione o primeiro atleta</option>

                        @if ($isAdmin)
                            {{-- ADMIN: todas as instituições --}}
                            @foreach ($instituicoes as $inst)
                                <optgroup label="{{ $inst->nome }}">
                                    @foreach ($inst->alunos as $aluno)
                                        <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @else
                            {{-- ATLETA/TÉCNICO: somente a própria instituição --}}
                            @foreach ($alunosInst as $aluno)
                                <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Atleta 2 --}}
                <div class="mb-3">
                    <select class="form-select" id="aluno2_id" name="aluno2_id" required
                        disabled
                        @disabled(!$isAdmin && !$instituicaoId)>
                        <option value="">Selecione o segundo atleta</option>

                        @if ($isAdmin)
                            {{-- ADMIN: todas as instituições --}}
                            @foreach ($instituicoes as $inst)
                                <optgroup label="{{ $inst->nome }}">
                                    @foreach ($inst->alunos as $aluno)
                                        <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @else
                            {{-- ATLETA/TÉCNICO: somente a própria instituição --}}
                            @foreach ($alunosInst as $aluno)
                                <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{-- Botões --}}
                <div class="row justify-content-center mt-1 g-3">
                    <div class="col-auto">
                        <button id="btn-narracao"
                                type="submit"
                                class="btn btn-primary btn-lg px-4"
                                style="background:#28365F;color:#fff;"
                                @disabled(!$isAdmin && !$instituicaoId)>
                            <span id="btn-text">Gerar Narração</span>
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('public.dashboard') }}"
                           class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-house-door me-1"></i> Voltar
                        </a>
                    </div>
                </div>
            </form>
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
