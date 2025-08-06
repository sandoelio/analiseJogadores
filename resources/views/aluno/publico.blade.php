@if(isset($mensagem))
  <div class="alert alert-warning">
    {{ $mensagem }}
  </div>
@else
  <div class="row g-4">
    {{-- Análise Atual --}}
    <div class="col-12 col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          Última Análise — {{ $atual->created_at->format('d/m/Y') }}
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">Arremesso: {{ $atual->arremesso }}</li>
          <li class="list-group-item">Passe: {{ $atual->passe }}</li>
          <li class="list-group-item">Marcação: {{ $atual->marcacao }}</li>
          <li class="list-group-item">Finalização: {{ $atual->finalizacao }}</li>
          <li class="list-group-item">Jogada: {{ $atual->jogada }}</li>
          <li class="list-group-item">Domínio: {{ $atual->dominio }}</li>
        </ul>
      </div>
    </div>

    {{-- Análise Anterior --}}
    <div class="col-12 col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
          Análise Anterior — {{ $anterior->created_at->format('d/m/Y') }}
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">Arremesso: {{ $anterior->arremesso }}</li>
          <li class="list-group-item">Passe: {{ $anterior->passe }}</li>
          <li class="list-group-item">Marcação: {{ $anterior->marcacao }}</li>
          <li class="list-group-item">Finalização: {{ $anterior->finalizacao }}</li>
          <li class="list-group-item">Jogada: {{ $anterior->jogada }}</li>
          <li class="list-group-item">Domínio: {{ $anterior->dominio }}</li>
        </ul>
      </div>
    </div>
  </div>
@endif
<div class="mt-5">
  <canvas id="comparativoGrafico" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('comparativoGrafico').getContext('2d');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Arremesso', 'Passe', 'Marcação', 'Finalização', 'Jogada', 'Domínio'],
      datasets: [
        {
          label: 'Última Análise',
          data: [
            {{ $atual->arremesso }},
            {{ $atual->passe }},
            {{ $atual->marcacao }},
            {{ $atual->finalizacao }},
            {{ $atual->jogada }},
            {{ $atual->dominio }}
          ],
          backgroundColor: 'rgba(54, 162, 235, 0.7)'
        },
        {
          label: 'Análise Anterior',
          data: [
            {{ $anterior->arremesso }},
            {{ $anterior->passe }},
            {{ $anterior->marcacao }},
            {{ $anterior->finalizacao }},
            {{ $anterior->jogada }},
            {{ $anterior->dominio }}
          ],
          backgroundColor: 'rgba(255, 99, 132, 0.7)'
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        title: {
          display: true,
          text: 'Comparativo de Desempenho'
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          max: 100 // Ajuste se sua escala for diferente
        }
      }
    }
  });
</script>
