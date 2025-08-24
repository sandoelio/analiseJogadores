@extends('layouts.app')

@section('title', 'Análise individual')

@push('styles')
    <style>
        /* overlay spinner genérico */
        .overlay-spinner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        /* wrapper relative para selects e chart */
        .field-wrapper,
        .chart-wrapper {
            position: relative;
        }

        /* estiliza o spinner maior */
        .overlay-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .back-logo {
            background: #28365F;
            margin-bottom: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">

        {{-- Logo --}}
        <div class="text-center mb-0">
            <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" style="max-width: 200px; width: 100%; height: auto;"
                class="back-logo" loading="lazy">
        </div>


        {{-- Botão Voltar --}}
        <div class="text-center my-3">
            <a href="{{ route('public.dashboard') }}" class="btn btn-primary" style="background: #28365F; color: #fff;">
                <i class="bi bi-house-door me-1"></i> Voltar
            </a>
        </div>

        {{-- Seleção de Instituição e Aluno --}}
        <div id="selecao-container" class="row gx-3 gy-3 mb-4 justify-content-center">

            <div id="instituicao-wrapper" class="col-12 col-md-6 field-wrapper">
                <label for="instituicao" class="form-label">
                    <i class="bi bi-building me-1"></i>Instituição
                </label>
                <select id="instituicao" class="form-select">
                    <option selected disabled>Selecione a instituição</option>
                    @foreach ($instituicoes as $inst)
                        <option value="{{ $inst->id }}">{{ $inst->nome }}</option>
                    @endforeach
                </select>
                {{-- overlay spinner para instituição --}}
                <div id="overlay-instituicao" class="overlay-spinner d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>

            <div id="aluno-wrapper" class="col-12 col-md-6 d-none field-wrapper">
                <label for="aluno" class="form-label">
                    <i class="bi bi-person-badge me-1"></i>Atleta
                </label>
                <select id="aluno" class="form-select">
                    <option selected disabled>Selecione um atleta</option>
                </select>
                {{-- overlay spinner para aluno --}}
                <div id="overlay-aluno" class="overlay-spinner d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>

        {{-- Gráfico de Estatísticas --}}
        <div id="estatisticas-container" class="card shadow-sm d-none chart-wrapper">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-bar-chart-fill fs-4 me-2"></i>
                <h5 class="mb-0">Estatísticas do Atleta</h5>
            </div>
            <div class="card-body p-3 d-flex justify-content-center" style="min-height:320px;">
                {{-- overlay spinner para o chart --}}
                <div id="overlay-chart" class="overlay-spinner d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
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
  const selectAluno    = document.getElementById('aluno');
  const wrapperInst    = document.getElementById('overlay-instituicao');
  const wrapperAluno   = document.getElementById('overlay-aluno');
  const wrapperChart   = document.getElementById('overlay-chart');
  const statsContainer = document.getElementById('estatisticas-container');
  const canvas         = document.getElementById('estatisticas-chart');
  let chartInstance    = null;

  const tplAlunos = "{{ route('analise.alunos', ['instituicao' => 'INSTITUICAO_ID']) }}";
  const tplShow   = "{{ route('analise.mostrar',   ['matricula'   => 'MATRICULA_ID']) }}";

  selectInst.addEventListener('change', () => {
    wrapperInst.classList.remove('d-none');

    fetch(tplAlunos.replace('INSTITUICAO_ID', selectInst.value))
      .then(res => {
        if (res.status === 429) throw new Error('Too Many Requests');
        return res.json();
      })
      .then(alunos => {
        wrapperInst.classList.add('d-none');
        selectAluno.innerHTML = '<option selected disabled>Selecione um atleta</option>';
        alunos.forEach(a => selectAluno.append(new Option(a.nome, a.matricula)));

        document.getElementById('instituicao-wrapper').classList.remove('text-center');
        document.getElementById('aluno-wrapper').classList.remove('d-none');
        statsContainer.classList.add('d-none');
      })
      .catch(err => {
        console.error(err);
        wrapperInst.classList.add('d-none');
      });
  });

  selectAluno.addEventListener('change', () => {
    // mostra spinner do aluno e do chart
    wrapperAluno.classList.remove('d-none');
    wrapperChart.classList.remove('d-none');
    statsContainer.classList.remove('d-none');
    canvas.style.display = 'none';

    fetch(tplShow.replace('MATRICULA_ID', selectAluno.value))
      .then(res => {
        if (res.status === 429) throw new Error('Too Many Requests');
        return res.json();
      })
      .then(data => {
        // esconde spinners
        wrapperAluno.classList.add('d-none');
        wrapperChart.classList.add('d-none');

        // exibe canvas e desenha
        canvas.style.display = '';
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
                maxBarThickness: 50
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
                ticks: { autoSkip: true, maxRotation: 45, minRotation: 0, font: { size: 12 } }
              },
              y: {
                beginAtZero: true,
                suggestedMax: 100,
                ticks: { stepSize: 10, font: { size: 12 } }
              }
            },
            layout: { padding: { top: 10, bottom: 10 } },
            plugins: {
              datalabels: {
                anchor: 'end',
                align: 'end',
                color: '#444',
                offset: 4,
                formatter: v => v
              },
              legend: { position: 'top' }
            }
          },
          plugins: [ChartDataLabels]
        });
      })
      .catch(err => {
        console.error(err);
        // esconde spinners em caso de erro
        wrapperAluno.classList.add('d-none');
        wrapperChart.classList.add('d-none');
        alert('Não foi possível carregar o gráfico. Tente novamente.');
      });
  });
});
</script>
@endpush
