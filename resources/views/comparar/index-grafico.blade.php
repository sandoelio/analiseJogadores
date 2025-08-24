@extends('layouts.app')

@section('title', 'Comparar Gráfico')

@push('styles')
    <style>
        #comparativo-chart {
            width: 100% !important;
            height: auto !important;
            max-width: 600px;
            min-height: 300px;
        }

        .back-logograph {
            background: #28365F;
            margin-bottom: 5px;
        }

        /* overlay semi-transparente com spinner */
        .overlay-spinner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
        .chart-wrapper {
            position: relative;
        }
    </style>
@endpush

@section('content')
    <div class="container">

        {{-- Logo --}}
        <div class="text-center mb-0">
            <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" style="max-width: 200px; width: 100%; height: auto;"
                class="back-logograph" loading="lazy">
        </div>

        {{-- Seleção --}}
        <div class="row g-3">
            <div class="col-md-6">
                <label for="aluno1" class="form-label">Atleta 1</label>
                <select id="aluno1" class="form-select">
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

            <div class="col-md-6">
                <label for="aluno2" class="form-label">Atleta 2</label>
                <select id="aluno2" class="form-select" disabled>
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
        </div>

        {{-- Botões --}}
        <div class="row justify-content-center mt-4">
            <div class="col-auto">
                <button id="btn-gerar-grafico" class="btn btn-lg" style="background: #28365F; color: white;" disabled>
                    Gerar Gráfico
                </button>
            </div>
            <div class="col-auto">
                <a href="{{ route('public.dashboard') }}" class="btn btn-lg" style="background: #28365F; color: white;">
                    <i class="bi bi-house-door me-1"></i>Voltar
                </a>
            </div>
        </div>

        {{-- Gráfico de Comparativo --}}
        <div id="chart-container" class="card shadow-sm mt-4 d-none chart-wrapper">
            {{-- overlay spinner do chart --}}
            <div id="overlay-chart" class="overlay-spinner d-none">
                <div class="spinner-border text-primary" role="status"></div>
            </div>

            <div class="card-header d-flex align-items-center">
                <i class="bi bi-bar-chart-fill fs-4 me-2"></i>
                <h5 class="mb-0">Gráfico</h5>
            </div>
            <div class="card-body d-flex justify-content-center p-3">
                <canvas id="comparativo-chart"></canvas>
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

            // bloqueia opção igual no segundo select
            sel1.addEventListener('change', () => {
                sel2.disabled = !sel1.value;
                Array.from(sel2.options).forEach(opt => {
                    opt.disabled = opt.value !== "" && opt.value === sel1.value;
                });
                sel2.value = "";
                btn.disabled = true;
                card.classList.add('d-none');
            });

            // habilita botão e oculta card
            sel2.addEventListener('change', () => {
                btn.disabled = !sel2.value;
                card.classList.add('d-none');
            });

            btn.addEventListener('click', () => {
                // mostra o card e o overlay spinner
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
                        // desenha o gráfico
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

                        // esconde o overlay
                        overlay.classList.add('d-none');
                    })
                    .catch(err => {
                        console.error(err);
                        // sempre esconda o overlay em caso de erro
                        overlay.classList.add('d-none');
                        alert('Não foi possível gerar o gráfico. Tente novamente.');
                    });
            });
        });
    </script>
@endpush
