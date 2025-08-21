@php
    $total    = $analises->count();
    $atual    = $analises->get(0); // null se não existir
    $anterior = $analises->get(1); // null se não existir
@endphp

@if ($total > 1)

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
          <li class="list-group-item">Bandeja: {{ $atual->bandeja }}</li>
          <li class="list-group-item">Rebote: {{ $atual->rebote }}</li>
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
          <li class="list-group-item">Bandeja: {{ $anterior->bandeja }}</li>
          <li class="list-group-item">Rebote: {{ $anterior->rebote }}</li>
          <li class="list-group-item">Domínio: {{ $anterior->dominio }}</li>
        </ul>
      </div>
    </div>
  </div>

  {{-- Gráfico comparativo --}}
  <div class="mt-5">
    <canvas id="comparativoGrafico" height="200"></canvas>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('comparativoGrafico').getContext('2d');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Arremesso', 'Passe', 'Marcação', 'Bandeja', 'Rebote', 'Domínio'],
        datasets: [
          {
            label: 'Última Análise',
            data: [
              {{ $atual->arremesso }},
              {{ $atual->passe }},
              {{ $atual->marcacao }},
              {{ $atual->bandeja }},
              {{ $atual->rebote }},
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
              {{ $anterior->bandeja }},
              {{ $anterior->rebote }},
              {{ $anterior->dominio }}
            ],
            backgroundColor: 'rgba(255, 99, 132, 0.7)'
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          title: {
            display: true,
            text: 'Comparativo de Desempenho'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100
          }
        }
      }
    });
  </script>

@elseif ($total === 1)

  {{-- Exibe a única análise --}}
  <div class="card shadow-sm mb-3">
    <div class="card-header bg-info text-white">
      Análise Única — {{ $atual->created_at->format('d/m/Y') }}
    </div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item">Arremesso: {{ $atual->arremesso }}</li>
      <li class="list-group-item">Passe: {{ $atual->passe }}</li>
      <li class="list-group-item">Marcação: {{ $atual->marcacao }}</li>
      <li class="list-group-item">Bandeja: {{ $atual->bandeja }}</li>
      <li class="list-group-item">Rebote: {{ $atual->rebote }}</li>
      <li class="list-group-item">Domínio: {{ $atual->dominio }}</li>
    </ul>
  </div>
  <div class="alert alert-warning">
    Este aluno possui apenas uma análise. Insira outra para ver o comparativo completo.
  </div>

@else

  {{-- Nenhuma análise --}}
  <div class="alert alert-info">
    Ainda não há análises para este aluno. Comece cadastrando a primeira análise.
  </div>

@endif
