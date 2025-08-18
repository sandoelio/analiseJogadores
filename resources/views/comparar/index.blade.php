@extends('layouts.app')

@section('title', 'Comparar Atletas')

@section('content')
      <div class="container my-4">
        <h2 class="mb-4">Simular Duelo 1x1 de Basquete</h2>
        <form action="{{ route('comparar.narrar') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="aluno1_id" class="form-label">Jogador 1</label>
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
                <label for="aluno2_id" class="form-label">Jogador 2</label>
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
                <button type="submit"class="btn btn-primary btn-lg px-4">
                  Gerar Narração
                </button>
              </div>
              <div class="col-auto">
                <a href="{{ route('analise.index') }}"class="btn btn-secondary btn-lg px-4">
                    Voltar para Análise
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

    sel1.addEventListener('change', () => atualizarOpcoes(sel1, sel2));
    sel2.addEventListener('change', () => atualizarOpcoes(sel2, sel1));
  });
</script>
@endpush