@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .grafico-shell {
            max-width: 1080px;
            margin: 0 auto;
            padding: 1rem 0 1.15rem;
        }

        .grafico-topo {
            margin-bottom: 0.95rem;
        }

        .grafico-heading,
        .grafico-card,
        .grafico-chart-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .grafico-heading {
            padding: 1rem 1.1rem;
        }

        .grafico-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.55rem;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            background: #eef3fb;
            color: #28365F;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .grafico-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .grafico-text {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .grafico-card {
            overflow: hidden;
        }

        .grafico-card-header,
        .grafico-chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.95rem 1.05rem;
            border-bottom: 1px solid #edf2f8;
        }

        .grafico-card-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.04rem;
            font-weight: 700;
        }

        .grafico-card-subtitle {
            margin: 0.18rem 0 0;
            color: #5f6b85;
            font-size: 0.84rem;
        }

        .grafico-badge {
            display: inline-flex;
            align-items: center;
            min-height: 34px;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #f5f8fd;
            border: 1px solid #dbe1ec;
            color: #44506b;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .grafico-form-wrap {
            padding: 1.05rem 1.1rem 1.1rem;
        }

        .grafico-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .grafico-campo {
            display: grid;
            gap: 0.45rem;
        }

        .grafico-label {
            color: #33405f;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .grafico-select {
            min-height: 48px;
            border-radius: 0.85rem;
            border-color: #dbe1ec;
            box-shadow: none;
        }

        .grafico-select:focus {
            border-color: #8ea3ce;
            box-shadow: 0 0 0 0.2rem rgba(40, 54, 95, 0.12);
        }

        .grafico-acoes {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .grafico-btn {
            min-height: 44px;
            padding: 0.6rem 1.2rem;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .grafico-btn-principal {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .grafico-btn-principal:hover,
        .grafico-btn-principal:focus {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .grafico-chart-card {
            position: relative;
            margin-top: 0.95rem;
            overflow: hidden;
        }

        .grafico-chart-wrap {
            position: relative;
            height: 380px;
            max-height: 380px;
            padding: 0.95rem 1rem 1rem;
            overflow: hidden;
        }

        #comparativo-chart {
            display: block;
            width: 100% !important;
            height: 100% !important;
            max-width: 100%;
            min-height: 0;
        }

        .overlay-spinner {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.82);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .overlay-spinner .spinner-border {
            width: 2.8rem;
            height: 2.8rem;
        }

        @media (max-width: 767.98px) {
            .grafico-shell {
                padding-top: 0.55rem;
            }

            .grafico-title {
                font-size: 1.22rem;
            }

            .grafico-card-header,
            .grafico-chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.55rem;
            }

            .grafico-grid {
                grid-template-columns: 1fr;
            }

            .grafico-acoes {
                flex-direction: column;
                align-items: stretch;
            }

            .grafico-btn {
                width: 100%;
            }

            .grafico-chart-wrap {
                height: 260px;
                max-height: 260px;
                padding: 0.8rem 0.85rem 0.9rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $user = auth()->user();
        $isAdmin = auth()->check() && (int) ($user->is_admin ?? 0) === 1;
        $instIdEfetiva = session('aluno_instituicao_id') ?? (auth()->check() ? $user->instituicao_id ?? null : null);
        $instLog = !$isAdmin && $instIdEfetiva ? $instituicoes->firstWhere('id', $instIdEfetiva) : null;
    @endphp

    <div class="container-fluid grafico-shell">
        <div class="grafico-topo">
            <div class="grafico-heading">
                <span class="grafico-chip">
                    <i class="bi bi-bar-chart-line"></i>
                    Grafico
                </span>
                <h1 class="grafico-title">Comparacao visual entre atletas</h1>
                <p class="grafico-text">
                    @if ($isAdmin)
                        Selecione dois atletas para gerar o comparativo em grafico, inclusive entre instituicoes diferentes.
                    @else
                        Selecione dois atletas disponiveis no seu acesso para comparar os dados da ultima analise.
                    @endif
                </p>
            </div>
        </div>

        <div class="grafico-card">
            <div class="grafico-card-header">
                <div>
                    <h2 class="grafico-card-title">Selecao dos atletas</h2>
                </div>

                <span class="grafico-badge">
                    <i class="bi bi-graph-up-arrow me-1"></i>
                    Comparativo visual
                </span>
            </div>

            <div class="grafico-form-wrap">
                <div class="grafico-grid">
                    <div class="grafico-campo">
                        <label for="aluno1" class="grafico-label">Primeiro atleta</label>
                        <select id="aluno1" class="form-select grafico-select">
                            <option value="">Selecione o primeiro atleta</option>
                            @if ($instLog)
                                @foreach ($instLog->alunos as $aluno)
                                    <option value="{{ $aluno->id }}" data-nome="{{ $aluno->nome }}"
                                        data-idade="{{ $aluno->idade }}">
                                        {{ $aluno->nome }} -
                                        {{ $aluno->idade !== null ? $aluno->idade . ' anos' : '--' }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($instituicoes as $inst)
                                    <optgroup label="{{ $inst->nome }}">
                                        @foreach ($inst->alunos as $aluno)
                                            <option value="{{ $aluno->id }}" data-nome="{{ $aluno->nome }}"
                                                data-idade="{{ $aluno->idade }}">
                                                {{ $aluno->nome }} -
                                                {{ $aluno->idade !== null ? $aluno->idade . ' anos' : '--' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="grafico-campo">
                        <label for="aluno2" class="grafico-label">Segundo atleta</label>
                        <select id="aluno2" class="form-select grafico-select" disabled>
                            <option value="">Selecione o segundo atleta</option>
                            @if ($instLog)
                                @foreach ($instLog->alunos as $aluno)
                                    <option value="{{ $aluno->id }}" data-nome="{{ $aluno->nome }}"
                                        data-idade="{{ $aluno->idade }}">
                                        {{ $aluno->nome }} -
                                        {{ $aluno->idade !== null ? $aluno->idade . ' anos' : '--' }}
                                    </option>
                                @endforeach
                            @else
                                @foreach ($instituicoes as $inst)
                                    <optgroup label="{{ $inst->nome }}">
                                        @foreach ($inst->alunos as $aluno)
                                            <option value="{{ $aluno->id }}" data-nome="{{ $aluno->nome }}"
                                                data-idade="{{ $aluno->idade }}">
                                                {{ $aluno->nome }} -
                                                {{ $aluno->idade !== null ? $aluno->idade . ' anos' : '--' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="grafico-acoes">
                    <a href="{{ route('public.dashboard') }}" class="btn btn-outline-secondary grafico-btn">
                        <i class="bi bi-house-door me-1"></i>
                        Voltar
                    </a>

                    <button id="btn-gerar-grafico" class="btn grafico-btn grafico-btn-principal" disabled>
                        Gerar grafico
                    </button>
                </div>
            </div>
        </div>

        <div id="chart-container" class="grafico-chart-card d-none">
            <div id="overlay-chart" class="overlay-spinner d-none">
                <div class="spinner-border text-primary" role="status"></div>
            </div>

            <div class="grafico-chart-header">
                <div>
                    <h2 class="grafico-card-title">Grafico comparativo</h2>
                    <p class="grafico-card-subtitle">Leitura visual dos campos tecnicos da ultima analise de cada atleta.</p>
                </div>

                <span class="grafico-badge">
                    <i class="bi bi-bar-chart-fill me-1"></i>
                    Resultado
                </span>
            </div>

            <div class="grafico-chart-wrap">
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
                            alert(`Voce atingiu o limite de requisicoes. Aguarde ${wait}s.`);
                            throw new Error('Too Many Requests');
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (chartInstance) {
                            chartInstance.destroy();
                        }

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
                        alert('Nao foi possivel gerar o grafico. Tente novamente.');
                    });
            });
        });
    </script>
@endpush
