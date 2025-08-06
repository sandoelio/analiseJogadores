@extends('layouts.app')

@section('title', 'Consulta Pública de Estatísticas')

@section('content')
<div class="container mt-4">

  <div class="d-flex justify-content-between mb-4">
    <h2>Consulta Pública de Estatísticas</h2>
  </div>

  <!-- Seleção de Instituição -->
  <div class="mb-3">
    <label for="instituicao">Instituição:</label>
    <select id="instituicao" class="form-select">
      <option selected disabled>Selecione uma instituição</option>
      @foreach ($instituicoes as $inst)
        <option value="{{ $inst->id }}">{{ $inst->nome }}</option>
      @endforeach
    </select>
  </div>

  <!-- Seleção de Aluno (inicialmente oculta) -->
  <div id="aluno-container" class="mb-3" style="display: none;">
    <label for="aluno">Aluno:</label>
    <select id="aluno" class="form-select">
      <option selected disabled>Selecione um aluno</option>
    </select>
  </div>

  <!-- Gráfico de Estatísticas (inicialmente oculto) -->
  <div id="estatisticas-container" class="mt-4" style="display: none;">
    <h4>Estatísticas do Aluno</h4>
    <canvas id="estatisticas-chart" height="200"></canvas>
  </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const selectInst     = document.getElementById('instituicao');
  const alunoContainer = document.getElementById('aluno-container');
  const selectAluno    = document.getElementById('aluno');
  const statsContainer = document.getElementById('estatisticas-container');
  const canvas         = document.getElementById('estatisticas-chart');
  let   chartInstance  = null;

  const tplAlunos = "{{ route('analise.alunos', ['instituicao' => 'INSTITUICAO_ID']) }}";
  const tplShow   = "{{ route('analise.mostrar', ['matricula'   => 'MATRICULA_ID']) }}";

  // Mudança de instituição carrega alunos
  selectInst.addEventListener('change', () => {
    fetch(tplAlunos.replace('INSTITUICAO_ID', selectInst.value))
      .then(res => res.json())
      .then(alunos => {
        selectAluno.innerHTML = '<option selected disabled>Selecione um aluno</option>';
        alunos.forEach(a => {
          selectAluno.append(new Option(a.nome, a.matricula));
        });
        alunoContainer.style.display = 'block';
        statsContainer.style.display = 'none';
      })
      .catch(console.error);
  });

  // Ao escolher aluno, busca as análises e desenha o gráfico
  selectAluno.addEventListener('change', () => {
    fetch(tplShow.replace('MATRICULA_ID', selectAluno.value))
      .then(res => res.json())
      .then(data => {
        const ctx = canvas.getContext('2d');

        // destrói gráfico anterior
        if (chartInstance) {
          chartInstance.destroy();
        }

        // cria novo gráfico de barras
        chartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [
              {
                label: 'Anterior',
                data: data.anterior,
                backgroundColor: 'rgba(255, 159, 64, 0.7)',
              },
              {
                label: 'Atual',
                data: data.atual,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
              }
            ]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
                max: 100
              }
            }
          }
        });

        statsContainer.style.display = 'block';
      })
      .catch(console.error);
  });
});
</script>
@endpush
