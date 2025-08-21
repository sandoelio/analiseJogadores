@extends('layouts.app')

@section('title', 'Comparar Atletas')

@push('styles')
<style>
  #comparativo-chart {
    width: 100% !important;
    height: auto !important;
    max-width: 600px;
    min-height: 300px;
  }
</style>
@endpush

@section('content')
<div class="container my-4">
  <h2 class="mb-4">Comparativo de Atributos</h2>

  <div class="row g-3">
    <div class="col-md-6">
      <label for="aluno1" class="form-label">Atleta 1</label>
      <select id="aluno1" class="form-select">
        <option value="">Selecione um atleta</option>
        @foreach($instituicoes as $inst)
          <optgroup label="{{ $inst->nome }}">
            @foreach($inst->alunos as $aluno)
              <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
            @endforeach
          </optgroup>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label for="aluno2" class="form-label">Atleta 2</label>
      <select id="aluno2" class="form-select" disabled>
        <option value="">Selecione um atleta</option>
        @foreach($instituicoes as $inst)
          <optgroup label="{{ $inst->nome }}">
            @foreach($inst->alunos as $aluno)
              <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
            @endforeach
          </optgroup>
        @endforeach
      </select>
    </div>
  </div>

  {{-- Botão para gerar gráfico --}}
  <div class="row justify-content-center mt-4">
    <div class="col-auto">
      <button id="btn-gerar-grafico"
              class="btn btn-lg" style="background: #28365F; color: white;"
              disabled>
        Gerar Gráfico
      </button>
    </div>
  </div>

  <div id="chart-container" class="card shadow-sm mt-4 d-none">
    <div class="card-header d-flex align-items-center">
      <i class="bi bi-bar-chart-fill fs-4 me-2"></i>
    </div>
    <div class="card-body d-flex justify-content-center p-3">
      <canvas id="comparativo-chart"></canvas>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const sel1   = document.getElementById('aluno1');
  const sel2   = document.getElementById('aluno2');
  const btn    = document.getElementById('btn-gerar-grafico');
  const card   = document.getElementById('chart-container');
  const ctx    = document.getElementById('comparativo-chart').getContext('2d');
  let chart    = null;
  const urlApi = "{{ route('comparar.grafico.dados') }}";

  // 1) Habilita o segundo select e impede dupla seleção
  sel1.addEventListener('change', () => {
    sel2.disabled = !sel1.value;
    Array.from(sel2.options).forEach(opt => {
      opt.disabled = opt.value === sel1.value && opt.value !== "";
    });
    sel2.value = "";
    btn.disabled = true;
    card.classList.add('d-none');
  });

  // 2) Quando escolher o segundo atleta, habilita o botão
  sel2.addEventListener('change', () => {
    btn.disabled = !sel2.value;
    card.classList.add('d-none');
  });

  // 3) Ao clicar em “Gerar Gráfico”, faz POST e renderiza
  btn.addEventListener('click', () => {
    fetch(urlApi, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        aluno1_id: sel1.value,
        aluno2_id: sel2.value
      })
    })
    .then(res => res.json())
    .then(data => {
      if (chart) chart.destroy();

      chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [
            {
              label: data.aluno1,
              data: data.values1,
              backgroundColor: 'rgba(54, 162, 235, 0.3)',
              borderColor:   'rgba(54, 162, 235, 1)',
              borderWidth:  1
            },
            {
              label: data.aluno2,
              data: data.values2,
              backgroundColor: 'rgba(255, 99, 132, 0.3)',
              borderColor:   'rgba(255, 99, 132, 1)',
              borderWidth:  1
            }
          ]
        },
        options: {
          scales: {
            r: {
              beginAtZero: true,
              suggestedMax: 100,
              ticks: { stepSize: 10, font: { size: 12 } }
            }
          },
          plugins: {
            legend: { position: 'top' },
            title:  { display: true, text: 'Perfil de Atributos' }
          }
        }
      });

      card.classList.remove('d-none');
    })
    .catch(console.error);
  });
});
</script>
@endpush

