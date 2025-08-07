{{-- resources/views/analise/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Análises de Atletas')

@push('styles')
<style>
  /* canvas ocupa 100% da largura e altura mínima */
  #estatisticas-chart {
    width: 100% !important;
    height: auto !important;
    max-width: 600px;
    min-height: 300px; /* aumenta a área vertical */
  }
</style>
@endpush

@section('content')
  <div class="container-fluid py-4">

    {{-- Logo --}}
    <div class="text-center mb-0">
      <img 
        src="{{ asset('imagem/slogan.png') }}" 
        alt="Logo Pirajá" 
        style="max-width: 100px; width: 100%; height: auto;"
        loading="lazy"
      >
    </div>

    {{-- Seleção de Instituição e Aluno --}}
    <div class="row gx-3 gy-3 mb-4">
      <div class="col-12 col-md-6">
        <label for="instituicao" class="form-label">
          <i class="bi bi-building me-1"></i>Instituição
        </label>
        <select id="instituicao" class="form-select">
          <option selected disabled>Instituição do atleta.</option>
          @foreach ($instituicoes as $inst)
            <option value="{{ $inst->id }}">{{ $inst->nome }}</option>
          @endforeach
        </select>
      </div>

      <div id="aluno-container" class="col-12 col-md-6 d-none">
        <label for="aluno" class="form-label">
          <i class="bi bi-person-badge me-1"></i>Aluno
        </label>
        <select id="aluno" class="form-select">
          <option selected disabled>Selecione um aluno</option>
        </select>
      </div>
    </div>

    {{-- Gráfico de Estatísticas --}}
    <div id="estatisticas-container" class="card shadow-sm d-none">
      <div class="card-header d-flex align-items-center">
        <i class="bi bi-bar-chart-fill fs-4 me-2"></i>
        <h5 class="mb-0">Estatísticas do Aluno</h5>
      </div>
      <div class="card-body p-3 d-flex justify-content-center">
        <canvas id="estatisticas-chart"></canvas>
      </div>
    </div>

  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const selectInst     = document.getElementById('instituicao');
  const alunoContainer = document.getElementById('aluno-container');
  const selectAluno    = document.getElementById('aluno');
  const statsContainer = document.getElementById('estatisticas-container');
  const canvas         = document.getElementById('estatisticas-chart');
  let chartInstance    = null;

  const tplAlunos = "{{ route('analise.alunos', ['instituicao' => 'INSTITUICAO_ID']) }}";
  const tplShow   = "{{ route('analise.mostrar', ['matricula'   => 'MATRICULA_ID']) }}";

  selectInst.addEventListener('change', () => {
    fetch(tplAlunos.replace('INSTITUICAO_ID', selectInst.value))
      .then(res => res.json())
      .then(alunos => {
        selectAluno.innerHTML = '<option selected disabled>Selecione um aluno</option>';
        alunos.forEach(a => selectAluno.append(new Option(a.nome, a.matricula)));
        alunoContainer.classList.remove('d-none');
        statsContainer.classList.add('d-none');
      })
      .catch(console.error);
  });

  selectAluno.addEventListener('change', () => {
    fetch(tplShow.replace('MATRICULA_ID', selectAluno.value))
      .then(res => res.json())
      .then(data => {
        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(canvas.getContext('2d'), {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [
              {
                label: 'Anterior',
                data: data.anterior,
                backgroundColor: 'rgba(255, 159, 64, 0.8)',
                borderRadius: 4,
                maxBarThickness: 50 // largura máxima, evita barras gigantes
              },
              {
                label: 'Atual',
                data: data.atual,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderRadius: 4,
                maxBarThickness: 50
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false, 
            scales: {
              x: {
                ticks: {
                  autoSkip: true,
                  maxTicksLimit: 6,
                  maxRotation: 45,
                  minRotation: 0,
                  font: { size: 12 }
                }
              },
              y: {
                beginAtZero: true,
                suggestedMax: 100,
                ticks: {
                  stepSize: 10,
                  font: { size: 12 }
                }
              }
            },
            layout: {
              padding: { top: 10, bottom: 10 }
            },
            plugins: {
              datalabels: {
                anchor: 'end',
                align: 'end',
                color: '#444',
                offset: 2,
                formatter: v => v,
                font: ctx => {
                  const w = ctx.chart.width;
                  return { size: w < 400 ? 8 : (w < 600 ? 10 : 12), weight: 'bold' };
                }
              },
              tooltip: {
                callbacks: {
                  label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}`
                }
              },
              legend: {
                position: 'top'
              }
            }
          },
          plugins: [ChartDataLabels]
        });

        statsContainer.classList.remove('d-none');
      })
      .catch(console.error);
  });
});
</script>
@endpush
