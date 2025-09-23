{{-- resources/views/analise/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise individual')

@push('styles')
    <style>
        #graficoFisico {
            min-height: 290px;
        }

        #graficoClinico {
            width: 100% !important;
            max-width: 600px;
            min-height: 290px;
            height: auto !important;
            display: block;
            margin: 0 auto;
        }


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

        .overlay-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        /* wrapper relative para selects e chart */
        .field-wrapper,
        .chart-wrapper {
            position: relative;
        }

        /* reduz e centraliza a logo */
        .back-logo {
            background: #28365F;
            display: block;
            margin: 0 auto 0.4rem;
            max-width: 200px;
            width: 100%;
            height: auto;
        }

        /* limita a largura dos selects e centraliza */
        .field-wrapper .form-select {
            display: block;
            margin: 0 auto;
            max-width: 600px;
            width: 100%;
        }

        /* opcional: limita também o botão “Voltar” */
        .volver-wrapper .btn {
            max-width: 200px;
            width: 100%;
            margin: 0 auto;
            display: block;
            background: #28365F;
        }

        /* canvas do Chart.js */
        #estatisticas-chart {
            width: 100% !important;
            height: auto !important;
            max-width: 600px;
            min-height: 300px;
            display: block;
            margin: 0 auto;
        }

        /* Ajustes específicos para desk*/
        @media (min-width: 768px) {

            /* remove qualquer scroll horizontal no desktop */
            html,
            body {
                overflow-x: hidden;
            }

            /* adiciona espaço entre navbar e logo */
            .back-logo {
                margin-top: 1rem;
                margin-bottom: 1.0rem;
            }

            /* reduz o espaçamento vertical entre selects */
            #selecao-container {
                --bs-gutter-y: .5rem;
                margin-bottom: 0.1rem !important;
            }

            #selecao-container .field-wrapper {
                width: 100% !important;
                max-width: 600px;
                /* largura máxima dos selects */
            }

            /* limita a largura do card de gráfico e centraliza */
            .chart-wrapper {
                max-width: 600px;
                margin: 0.5rem auto 2rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $atletaInst = session('aluno_instituicao_id');
    @endphp

    <div class="container-fluid">
        {{-- Logo --}}
        <div class="text-center back-logo">
            <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" style="max-width:150px; width:80%;" loading="lazy">
        </div>

        {{-- Voltar --}}
        <div class="row justify-content-center mb-2 volver-wrapper">
            <div class="col-12 col-md-6 text-center">
                <a href="{{ route('public.dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house-door me-1"></i> Voltar
                </a>
            </div>
        </div>

        {{-- SELEÇÃO --}}
        <div id="selecao-container" class="row gx-1 gy-1 justify-content-center mb-0">
            @if ($atletaInst)
                {{-- Usuário atleta: só seleciona o próprio atleta --}}
                <div class="col-12 col-md-6 position-relative field-wrapper">
                    <select id="aluno" class="form-select">
                        <option selected disabled>Carregando atletas…</option>
                    </select>
                    <div id="overlay-aluno" class="overlay-spinner">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            @else
                {{-- Público/Admin/Técnico: escolhe instituição e depois atleta --}}
                <div id="selecao-container" class="d-flex flex-column align-items-center mb-1"
                    style="flex-direction: column">
                    <div id="instituicao-wrapper" class="position-relative field-wrapper w-100">
                        <select id="instituicao" class="form-select">
                            <option selected disabled>Selecione a instituição</option>
                            @foreach ($instituicoes as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->nome }}</option>
                            @endforeach
                        </select>
                        <div id="overlay-instituicao" class="overlay-spinner d-none">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>

                    <div id="aluno-wrapper" class="position-relative field-wrapper w-100 mt-3 d-none">
                        <select id="aluno" class="form-select">
                            <option selected disabled>Selecione o atleta</option>
                        </select>
                        <div id="overlay-aluno" class="overlay-spinner d-none">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- GRÁFICO --}}
        <div id="estatisticas-container" class="card shadow-sm d-none chart-wrapper">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-bar-chart-fill fs-4 me-2"></i>
                    <h5 class="mb-0">Estatísticas do Atleta</h5>
                </div>

                @php
                    $isPrivilegiado = auth()->check() && !session()->has('aluno_instituicao_id');
                @endphp

                @if ($isPrivilegiado)
                    <!-- Botão Nova Análise Física -->
                    <button class="btn btn-lg btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#modalAnaliseFisica"
                        onclick="carregarGraficosExtras(document.getElementById('aluno').value)">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </button>

                    <!-- Botão Saúde do Atleta -->
                    <button class="btn btn-lg btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalSaudeAtleta"
                        onclick="carregarGraficosExtras(document.getElementById('aluno').value)">
                        <i class="bi bi-clipboard2-heart"></i>
                    </button>
                @endif
            </div>

            <div class="card-body p-3 d-flex justify-content-center" style="min-height:320px;position:relative;">
                <div id="overlay-chart" class="overlay-spinner d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <canvas id="estatisticas-chart"></canvas>
            </div>
        </div>
    </div>

    {{-- Modal: Análise Física --}}
    <div class="modal fade" id="modalAnaliseFisica" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Atributos Físicos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <canvas id="graficoFisico"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Saúde do Atleta --}}
    <div class="modal fade" id="modalSaudeAtleta" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Classificação Corporal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <canvas id="graficoClinico" style="max-height:350px;"></canvas>
                    <div class="mt-3 text-center">
                        <strong>Classificação:</strong> <span id="classificacaoLabel" class="badge bg-info"></span>
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
        window.ATLETA_INST = @json($atletaInst);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ------------------------------------------------
            // Referências de elementos
            // ------------------------------------------------
            const instWrapper = document.getElementById('instituicao-wrapper');
            const selectInst = document.getElementById('instituicao');
            const selectAluno = document.getElementById('aluno');
            const overlayInst = document.getElementById('overlay-instituicao');
            const overlayAluno = document.getElementById('overlay-aluno');
            const overlayChart = document.getElementById('overlay-chart');
            const statsCont = document.getElementById('estatisticas-container');
            const canvas = document.getElementById('estatisticas-chart');
            let chartInstance = null;

            const tplAlunos = "{{ route('analise.alunos', ['instituicao' => 'INSTITUICAO_ID']) }}";
            const tplShow = "{{ route('analise.mostrar', ['matricula' => 'MATRICULA_ID']) }}";

            // ------------------------------------------------
            // Carrega atletas automaticamente se for atleta
            // ------------------------------------------------
            if (window.ATLETA_INST) {
                overlayAluno.classList.remove('d-none');
                fetch(tplAlunos.replace('INSTITUICAO_ID', String(window.ATLETA_INST)))
                    .then(r => r.json())
                    .then(alunos => {
                        overlayAluno.classList.add('d-none');
                        selectAluno.innerHTML = '<option selected disabled>Selecione o atleta</option>';
                        alunos.forEach(a => selectAluno.append(new Option(a.nome, a.matricula)));
                    })
                    .catch(() => {
                        overlayAluno.classList.add('d-none');
                        alert('Falha ao carregar atletas');
                    });
            }

            // ------------------------------------------------
            // Selecionou instituição → carrega atletas
            // ------------------------------------------------
            if (selectInst) {
                selectInst.addEventListener('change', () => {
                    overlayInst.classList.remove('d-none');
                    fetch(tplAlunos.replace('INSTITUICAO_ID', selectInst.value))
                        .then(r => r.json())
                        .then(alunos => {
                            overlayInst.classList.add('d-none');
                            document.getElementById('aluno-wrapper').classList.remove('d-none');
                            selectAluno.innerHTML =
                                '<option selected disabled>Selecione o atleta</option>';
                            alunos.forEach(a => selectAluno.append(new Option(a.nome, a.matricula)));
                            statsCont.classList.add('d-none');
                        })
                        .catch(() => {
                            overlayInst.classList.add('d-none');
                        });
                });
            }

            // ------------------------------------------------
            // Escolheu atleta → mostra gráfico principal
            // ------------------------------------------------
            if (selectAluno) {
                selectAluno.addEventListener('change', () => {
                    overlayAluno.classList.remove('d-none');
                    overlayChart.classList.remove('d-none');
                    statsCont.classList.remove('d-none');
                    canvas.style.display = 'none';

                    fetch(tplShow.replace('MATRICULA_ID', selectAluno.value))
                        .then(r => r.json())
                        .then(data => {
                            overlayAluno.classList.add('d-none');
                            overlayChart.classList.add('d-none');
                            canvas.style.display = '';
                            if (chartInstance) chartInstance.destroy();

                            chartInstance = new Chart(canvas.getContext('2d'), {
                                type: 'bar',
                                data: {
                                    labels: data.labels,
                                    datasets: [{
                                            label: 'Anterior',
                                            data: data.anterior,
                                            backgroundColor: 'rgba(255,159,64,0.7)',
                                            borderRadius: 4,
                                            maxBarThickness: 50
                                        },
                                        {
                                            label: 'Atual',
                                            data: data.atual,
                                            backgroundColor: 'rgba(75,192,192,0.8)',
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
                                                maxRotation: 45,
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            suggestedMax: 20,
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
                                        datalabels: {
                                            anchor: 'end',
                                            align: 'end',
                                            color: '#444',
                                            offset: 4,
                                            formatter: v => v
                                        },
                                        legend: {
                                            position: 'top'
                                        }
                                    }
                                },
                                plugins: [ChartDataLabels]
                            });
                        })
                        .catch(() => {
                            overlayAluno.classList.add('d-none');
                            overlayChart.classList.add('d-none');
                            alert('Não foi possível carregar o gráfico.');
                        });
                });
            }

            // ------------------------------------------------
            // Gráficos dos modais: físicos e clínicos
            // ------------------------------------------------
            let graficoFisicoInstance = null;
            let graficoClinicoInstance = null;

            window.carregarGraficosExtras = function(matricula) {
                const baseURL = window.location.origin;
                const url = `${baseURL}/analise/extras/${matricula}`;

                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        // Gráfico físico (comparativo)
                        const ctxFisico = document.getElementById('graficoFisico').getContext('2d');
                        if (graficoFisicoInstance) graficoFisicoInstance.destroy();
                        graficoFisicoInstance = new Chart(ctxFisico, {
                            type: 'bar',
                            data: {
                                labels: data.fisico.labels,
                                datasets: [{
                                        label: 'Anterior',
                                        data: data.fisico.anterior,
                                        backgroundColor: 'rgba(255,159,64,0.7)',
                                        borderRadius: 4,
                                        maxBarThickness: 50
                                    },
                                    {
                                        label: 'Atual',
                                        data: data.fisico.atual,
                                        backgroundColor: 'rgba(75,192,192,0.8)',
                                        borderRadius: 4,
                                        maxBarThickness: 50
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: value => Number.isInteger(value) ? value : ''
                                            
                                        }
                                    }
                                },
                                plugins: {
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end',
                                        color: '#444',
                                        offset: 4,
                                        formatter: v => v
                                    },
                                    legend: {
                                        position: 'top'
                                    }
                                }
                            },
                            plugins: [ChartDataLabels]
                        });

                        // Gráfico clínico (comparativo)
                        const ctxClinico = document.getElementById('graficoClinico').getContext('2d');
                        if (graficoClinicoInstance) graficoClinicoInstance.destroy();
                        graficoClinicoInstance = new Chart(ctxClinico, {
                            type: 'bar',
                            data: {
                                labels: data.clinico.labels,
                                datasets: [{
                                        label: 'Anterior',
                                        data: data.clinico.anterior,
                                       backgroundColor: 'rgba(255,159,64,0.7)',
                                        borderRadius: 4,
                                        maxBarThickness: 50
                                    },
                                    {
                                        label: 'Atual',
                                        data: data.clinico.atual,
                                        backgroundColor: 'rgba(75,192,192,0.8)',
                                        borderRadius: 4,
                                        maxBarThickness: 50
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: value => Number.isInteger(value) ? value : ''
                                        }
                                    }
                                },
                                plugins: {
                                    datalabels: {
                                        anchor: 'end',
                                        align: 'end',
                                        color: '#444',
                                        offset: 4,
                                        formatter: v => v
                                    },
                                    legend: {
                                        position: 'top'
                                    }
                                }
                            },
                            plugins: [ChartDataLabels]
                        });

                        // Classificação corporal
                        const classificacao = document.getElementById('classificacaoLabel');
                        if (classificacao) {
                            classificacao.textContent = data.classificacao;
                            classificacao.classList.remove('d-none');
                        }
                    })
                    .catch(() => {
                        alert('Erro ao carregar dados físicos e clínicos.');
                    });
            };

            // ------------------------------------------------
            // Limpa gráficos ao fechar modais
            // ------------------------------------------------
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('hidden.bs.modal', () => {
                    if (graficoFisicoInstance) {
                        graficoFisicoInstance.destroy();
                        graficoFisicoInstance = null;
                    }
                    if (graficoClinicoInstance) {
                        graficoClinicoInstance.destroy();
                        graficoClinicoInstance = null;
                    }
                    const classificacao = document.getElementById('classificacaoLabel');
                    if (classificacao) {
                        classificacao.textContent = '';
                        classificacao.classList.add('d-none');
                    }
                });
            });
        });
    </script>
@endpush
