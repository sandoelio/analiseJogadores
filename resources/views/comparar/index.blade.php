{{-- resources/views/comparativo/narracao.blade.php --}}
@extends('layouts.app')

@section('title', 'Narração Atletas')

@push('styles')
    <style>
        .back-logonarracao {
            background: #28365F;
            margin-bottom: 5px;
        }

        /* Spinner overlay para o formulário inteiro */
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

        /* Wrapper relativo para posicionar o overlay */
        .form-wrapper {
            position: relative;
        }
    </style>
@endpush

@section('content')
    <div class="container my-4">

        {{-- 1) Mensagem de erro / throttle --}}
        @if (session('error'))
            <div class="alert alert-warning text-center">
                {{ session('error') }}
            </div>
        @endif

        {{-- Logo --}}
        <div class="text-center mb-0">
            <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" class="back-logonarracao"
                style="max-width:200px; width:100%; height:auto;" loading="lazy">
        </div>

        {{-- Formulário com overlay-wrapper --}}
        <div class="form-wrapper mt-4">
            {{-- overlay que aparece no submit --}}
            <div id="overlay-narracao" class="overlay-spinner d-none">
                <div class="spinner-border text-primary" role="status"></div>
            </div>

            <form id="narracao-form" action="{{ route('comparar.narrar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="aluno1_id" class="form-label">Atleta 1</label>
                    <select class="form-select" id="aluno1_id" name="aluno1_id" required>
                        <option value="">Selecione um atleta</option>
                        @foreach ($instituicoes as $inst)
                            <optgroup label="{{ $inst->nome }}">
                                @foreach ($inst->alunos as $aluno)
                                    <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="aluno2_id" class="form-label">Atleta 2</label>
                    <select class="form-select" id="aluno2_id" name="aluno2_id" required disabled>
                        <option value="">Selecione um atleta</option>
                        @foreach ($instituicoes as $inst)
                            <optgroup label="{{ $inst->nome }}">
                                @foreach ($inst->alunos as $aluno)
                                    <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="row justify-content-center mt-4 g-3">
                    <div class="col-auto">
                        <button id="btn-narracao" type="submit" class="btn btn-primary btn-lg px-4"
                            style="background:#28365F; color:white;">
                            <span id="btn-text">Gerar Narração</span>
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('public.dashboard') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-house-door me-1"></i>Voltar
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
                sel2.disabled = !sel1.value;
                sel2.value = '';
                atualizarOpcoes(sel1, sel2);
            });

            sel2.addEventListener('change', () => {
                atualizarOpcoes(sel2, sel1);
            });

            form.addEventListener('submit', (e) => {
                // mostra overlay e desabilita botão
                overlay.classList.remove('d-none');
                btn.disabled = true;
                btnText.textContent = 'Carregando...';
                // deixa o formulário prosseguir com o POST normalmente
            });
        });
    </script>
@endpush
