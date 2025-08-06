{{-- resources/views/analise/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Consulta Pública de Estatísticas')

@section('content')

<div class="container mt-4">

  <div class="d-flex justify-content-between mb-4">
    <h2>Consulta Pública de Estatísticas</h2>
  </div>

  <!-- Instituições -->
  <div class="mb-3">
    <label for="instituicao">Instituição:</label>
    <select id="instituicao" class="form-select">
      <option selected disabled>Selecione uma instituição</option>
      @foreach ($instituicoes as $inst)
        <option value="{{ $inst->id }}">{{ $inst->nome }}</option>
      @endforeach
    </select>
  </div>

  <!-- Alunos -->
  <div id="aluno-container" class="mb-3" style="display: none;">
    <label for="aluno">Aluno:</label>
    <select id="aluno" class="form-select">
      <option selected disabled>Selecione um aluno</option>
    </select>
  </div>

  <!-- Estatísticas -->
  <div id="estatisticas-container" class="mt-4" style="display: none;">
    <h4>Estatísticas do Aluno</h4>
    <div id="estatisticas-content" class="p-3 border rounded bg-light">
      <!-- HTML será injetado aqui -->
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const selectInst      = document.getElementById('instituicao');
    const alunoContainer  = document.getElementById('aluno-container');
    const selectAluno     = document.getElementById('aluno');
    const statsContainer  = document.getElementById('estatisticas-container');
    const contentStats    = document.getElementById('estatisticas-content');

    // Templates de rota com placeholders
    const urlAlunosTpl   = "{{ route('analise.alunos', ['instituicao' => 'INSTITUICAO_ID']) }}";
    const urlMostrarTpl  = "{{ route('analise.mostrar', ['matricula'   => 'MATRICULA_ID']) }}";

    // Ao mudar a instituição
    selectInst.addEventListener('change', () => {
      const instId = selectInst.value;
      const url    = urlAlunosTpl.replace('INSTITUICAO_ID', instId);

      fetch(url)
        .then(res => {

          return res.json();
        })
        .then(json => {

          // Limpa e preenche o select de aluno
          selectAluno.innerHTML = '<option selected disabled>Selecione um aluno</option>';
          (json.data || json).forEach(a => {
            const option = new Option(a.nome, a.matricula);
            selectAluno.appendChild(option);
          });

          // Exibe o container de alunos e esconde estatísticas
          alunoContainer.style.display = 'block';
          statsContainer.style.display = 'none';
        })
    });

    // Ao selecionar um aluno
    selectAluno.addEventListener('change', () => {
      const matricula = selectAluno.value;
      const url       = urlMostrarTpl.replace('MATRICULA_ID', matricula);

      fetch(url)
        .then(res => res.text())
        .then(html => {
          contentStats.innerHTML   = html;
          statsContainer.style.display = 'block';
        })
        .catch(err => console.error('❌ Erro ao carregar estatísticas:', err));
    });
  });
</script>
@endpush
