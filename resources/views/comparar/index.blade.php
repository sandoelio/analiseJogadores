@extends('layouts.app')

@section('title', 'Narração Atletas')

<style>
    .back-logonarracao {
        background: #28365F;
        margin-bottom: 5px
    }
</style>

@section('content')

    <div class="container my-4">
        <div class="container my-4">
            {{-- 1) Mensagem de erro / throttle --}}
            @if (session('error'))
                <div class="alert alert-warning text-center">
                    {{ session('error') }}
                </div>
            @endif
            {{-- Logo --}}
            <div class="text-center mb-0">
                <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana"
                    style="max-width: 200px; width: 100%; height: auto;" class="back-logonarracao" loading="lazy">
            </div>

            <form action="{{ route('comparar.narrar') }}" method="POST">
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
                    <select class="form-select" id="aluno2_id" name="aluno2_id" required>
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
                        <button type="submit"class="btn btn-primary btn-lg px-4"
                            style="background: #28365F; color: white;">
                            Gerar Narração
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('public.dashboard') }}"class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-house-door me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sel1 = document.getElementById('aluno1_id');
                const sel2 = document.getElementById('aluno2_id');

                function atualizarOpcoes(origem, alvo) {
                    const idSelecionado = origem.value;
                    Array.from(alvo.options).forEach(opt => {
                        // desabilita apenas as opções que tenham value igual ao selecionado (e não sejam placeholder)
                        opt.disabled = opt.value !== '' && opt.value === idSelecionado;
                    });
                }

                sel1.addEventListener('change', () => {
                    // habilita o segundo select quando escolher o primeiro
                    sel2.disabled = !sel1.value;
                    sel2.value = ''; // reseta seleção anterior
                    atualizarOpcoes(sel1, sel2); // bloqueia opção duplicada
                });

                sel2.addEventListener('change', () => {
                    atualizarOpcoes(sel2, sel1); // bloqueia no primeiro a opção escolhida no segundo
                });
            });
        </script>
    @endpush
