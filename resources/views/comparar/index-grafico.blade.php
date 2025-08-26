{{-- resources/views/comparar/grafico.blade.php --}}
@extends('layouts.app')

@section('title', 'Comparar Gráfico')

@push('styles')
<style>
  .central-column {
    max-width: 500px;
    width: 100%;
    margin: 0 auto;
    padding-bottom: 80px;        /* folga para não encostar no footer fixo */
    overflow-x: hidden;          /* previne rolagem horizontal no mobile */
  }

  .central-column .back-logograph {
    display: block;
    margin: 8px auto 1rem;
    max-width: 200px;
    width: 100%;
    background: #28365F;
    height: auto;
  }

  .central-column .form-select {
    width: 100%;
    margin-bottom: 1rem;
  }

  /* botões lado a lado sem estourar com o gap */
  .central-column .d-flex {
    gap: .5rem;
  }
  .central-column .d-flex .btn {
    flex: 1 1 0;     /* divide igualmente a linha */
    min-width: 0;    /* evita overflow por conteúdo longo */
  }

  .chart-wrapper {
    position: relative;
    margin-top: 1rem;
    margin-bottom: 0;
  }

  /* canvas 100% da coluna, sem exceder */
  #comparativo-chart {
    display: block;
    width: 100% !important;
    max-width: 100%;
    height: auto !important;
    min-height: 300px;
  }

  .overlay-spinner {
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
  }
  .overlay-spinner .spinner-border {
    width: 3rem;
    height: 3rem;
  }

  @media (max-width: 576px) {
    #comparativo-chart {
      min-height: 240px;
    }
  }

  /* telas muito estreitas: bota os botões em duas linhas */
  @media (max-width: 400px) {
    .central-column .d-flex {
      flex-wrap: wrap;
    }
    .central-column .d-flex .btn {
      flex: 1 0 100%;
    }
  }

  /* segurança extra contra rolagem lateral em todo o documento */
  html, body {
    overflow-x: hidden;
  }
</style>
@endpush


@section('content')
    @php
        $instId = session('aluno_instituicao_id');
        $instLog = $instId ? $instituicoes->firstWhere('id', $instId) : null;
    @endphp

    <div class="container">
        <div class="central-column">
            {{-- Logo --}}
            <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" class="back-logograph" loading="lazy">

            {{-- Atleta 1 --}}
            <select id="aluno1" class="form-select">
                <option value="">Selecione o primeiro atleta</option>
                @if ($instLog)
                    @foreach ($instLog->alunos as $aluno)
                        <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                    @endforeach
                @else
                    @foreach ($instituicoes as $inst)
                        <optgroup label="{{ $inst->nome }}">
                            @foreach ($inst->alunos as $aluno)
                                <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @endif
            </select>

            {{-- Atleta 2 --}}
            <select id="aluno2" class="form-select" disabled>
                <option value="">Selecione o segundo atleta</option>
                @if ($instLog)
                    @foreach ($instLog->alunos as $aluno)
                        <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                    @endforeach
                @else
                    @foreach ($instituicoes as $inst)
                        <optgroup label="{{ $inst->nome }}">
                            @foreach ($inst->alunos as $aluno)
                                <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                @endif
            </select>

            {{-- Botões lado a lado, metade cada --}}
            <div class="d-flex gap-2 mb-3">
                <button id="btn-gerar-grafico" class="btn btn-primary btn-lg flex-fill" disabled>
                    Gerar Gráfico
                </button>
                <a href="{{ route('public.dashboard') }}" class="btn btn-secondary btn-lg flex-fill">
                    <i class="bi bi-house-door me-1"></i> Voltar
                </a>
            </div>

            {{-- Gráfico --}}
            <div>

                <div id="chart-container" class="card shadow-sm d-none chart-wrapper">
                    <div id="overlay-chart" class="overlay-spinner d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div class="card-header d-flex align-items-center">
                        <i class="bi bi-bar-chart-fill fs-4 me-2"></i>
                        <h5 class="mb-0">Gráfico</h5>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <canvas id="comparativo-chart"></canvas>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sel1 = document.getElementById('aluno1');
            const sel2 = document.getElementById('aluno2');
            const btn = document.getElementById('btn-gerar-grafico');
            const card = document.getElementById('chart-container');
            const overlay = document.getElementById('overlay-chart');
            const ctx = document.getElementById('comparativo-chart').getContext('2d');
            let chartInstance = null;
            const urlApi = "{{ route('comparar.grafico.dados') }}";

            sel1.addEventListener('change', () => {
                sel2.disabled = !sel1.value;
                Array.from(sel2.options).forEach(opt => {
                    opt.disabled = opt.value === sel1.value && opt.value !== "";
                });
                sel2.value = "";
                btn.disabled = true;
                card.classList.add('d-none');
            });

            sel2.addEventListener('change', () => {
                btn.disabled = !sel2.value;
                card.classList.add('d-none');
            });

            btn.addEventListener('click', () => {
                card.classList.remove('d-none');
                overlay.classList.remove('d-none');

                fetch(urlApi, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            aluno1_id: sel1.value,
                            aluno2_id: sel2.value
                        })
                    })
                    .then(res => {
                        if (res.status === 429) {
                            const wait = res.headers.get('Retry-After') || 60;
                            alert(`Você atingiu o limite de requisições. Aguarde ${wait}s.`);
                            throw new Error('Too Many Requests');
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (chartInstance) chartInstance.destroy();
                        chartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                        label: data.aluno1,
                                        data: data.values1,
                                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                        borderRadius: 4,
                                        maxBarThickness: 50
                                    },
                                    {
                                        label: data.aluno2,
                                        data: data.values2,
                                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
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
                                            font: {
                                                size: 12
                                            }
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        suggestedMax: 100,
                                        ticks: {
                                            stepSize: 10,
                                            font: {
                                                size: 12
                                            }
                                        }
                                    }
                                },
                                layout: {
                                    padding: {
                                        top: 10,
                                        bottom: 10
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    },
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end',
                                        color: '#444',
                                        offset: 4,
                                        formatter: v => v
                                    }
                                }
                            },
                            plugins: [ChartDataLabels]
                        });

                        overlay.classList.add('d-none');
                    })
                    .catch(err => {
                        console.error(err);
                        overlay.classList.add('d-none');
                        alert('Não foi possível gerar o gráfico. Tente novamente.');
                    });
            });
        });
    </script>
@endpush
