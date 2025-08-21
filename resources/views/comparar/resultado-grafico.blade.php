@extends('layouts.app')

@section('title', 'Resultado em Gráfico')

@section('content')
<div class="container my-5">

  <h2 class="mb-4">Comparação de Estatísticas</h2>

  <canvas id="comparisonChart" height="300"></canvas>

  <div class="mt-4">
    <a href="{{ route('comparar.grafico.index') }}" class="btn btn-secondary">
      ← Nova Comparação
    </a>
    <a href="{{ route('comparar.index') }}" class="btn btn-link">
      ↩ Voltar ao Duelo Narrado
    </a>
  </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = @json($labels);
  const data = {
    labels: labels,
    datasets: [
      {
        label: '{{ $aluno1->nome }}',
        data: @json($values1),
        backgroundColor: 'rgba(54, 162, 235, 0.3)',
        borderColor:   'rgba(54, 162, 235, 1)',
        borderWidth:  1
      },
      {
        label: '{{ $aluno2->nome }}',
        data: @json($values2),
        backgroundColor: 'rgba(255, 99, 132, 0.3)',
        borderColor:   'rgba(255, 99, 132, 1)',
        borderWidth:  1
      }
    ]
  };

  const config = {
    type: 'radar',
    data: data,
    options: {
      scales: {
        r: {
          beginAtZero: true,
          suggestedMax: 10,
          ticks: { stepSize: 1 }
        }
      },
      plugins: {
        legend: { position: 'top' },
        title:  { display: true, text: 'Perfil de Atributos' }
      }
    }
  };

  new Chart(
    document.getElementById('comparisonChart'),
    config
  );
</script>
@endsection
