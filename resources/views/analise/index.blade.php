{{-- resources/views/analise/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise de Desempenhos')

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

        /* Timeline vertical base */
        .timeline {
            position: relative;
            padding-left: 36px;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        /* linha vertical */
        .timeline::before {
            content: "";
            position: absolute;
            left: 14px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, rgba(0, 123, 255, 0.15), rgba(0, 0, 0, 0.04));
            border-radius: 2px;
        }

        /* item da timeline */
        .timeline-item {
            position: relative;
            padding: 10px 12px 10px 22px;
            margin-bottom: 14px;
            border-radius: 6px;
            background: transparent;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* marcador circular */
        .timeline-marker {
            position: absolute;
            left: 2px;
            top: 14px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        }

        /* cores por tipo */
        .marker-created {
            background: #28a745;
        }

        .marker-updated {
            background: #0d6efd;
        }

        .marker-other {
            background: #6c757d;
        }

        /* pequeno ícone dentro do círculo (usando pseudo texto) */
        .timeline-marker i {
            font-style: normal;
            font-size: 12px;
        }

        /* conteúdo principal do card à direita */
        .timeline-content {
            margin-left: 44px;
            width: 100%;
        }

        /* cabeçalho (time + user) */
        .timeline-header {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .timeline-time {
            font-weight: 700;
        }

        .timeline-user {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* resumo curto */
        .timeline-summary {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 6px;
        }

        /* botão detalhes alinhado à direita pequena margem */
        .timeline-actions {
            margin-left: 12px;
            flex-shrink: 0;
        }

        /* destaque para evento do dia inicial */
        .timeline-item:first-child .timeline-marker {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.18);
            transform: scale(1.03);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            /* espaço padrão entre botões no desktop */
            align-items: center;
        }

        /* Modal Timeline — fundo suave azul claro */
        #modalTimeline .modal-content {
            background: linear-gradient(180deg, #f0f8ff 0%, #ffffff 100%);
            color: #0d2433;
            border: 1px solid rgba(13, 37, 51, 0.06);
            box-shadow: 0 8px 24px rgba(13, 37, 51, 0.08);
        }

        /* Modal Detalhes do Evento — fundo suave bege/creme */
        #modalEventoDetalhes .modal-content {
            background: linear-gradient(180deg, #fffaf0 0%, #ffffff 100%);
            color: #342a1a;
            border: 1px solid rgba(52, 42, 26, 0.06);
            box-shadow: 0 8px 24px rgba(52, 42, 26, 0.06);
        }

        /* Ajustes opcionais: cabeçalho transparente para harmonizar com o novo fundo */
        #modalTimeline .modal-header,
        #modalEventoDetalhes .modal-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

        /* Garantir leitura dos botões (ex.: btn-close) */
        #modalTimeline .btn-close,
        #modalEventoDetalhes .btn-close {
            filter: none;
            opacity: 0.9;
        }

        /* responsividade */
        @media (max-width: 576px) {
            .timeline {
                padding-left: 28px;
            }

            .timeline-marker {
                left: -2px;
            }

            .timeline-content {
                margin-left: 40px;
            }

            .action-buttons {
                gap: 0.75rem;
                justify-content: center;
            }

            /* botões maiores ganham margem inferior para evitar agrupamento visual */
            .action-buttons .btn {
                margin-bottom: 0.5rem;
            }

            /* se quiser empilhar em coluna com largura total dos botões */
            .action-buttons.stack-mobile .btn {
                width: 100%;
            }
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
        {{-- <div class="text-center back-logo">
            <img src="{{ asset('imagem/LOGO1.png') }}" alt="Cesta Baiana" style="max-width:150px; width:80%;" loading="lazy">
        </div> --}}

        {{-- Voltar --}}
        <div class="row justify-content-center mb-2 mt-2 volver-wrapper">
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
                    <h6 class="mb-0">Estatísticas do Atleta</h6>
                </div>

                @php
                    $isPrivilegiado = auth()->check() && !session()->has('aluno_instituicao_id');
                @endphp

                @if ($isPrivilegiado)
                    <div class="action-buttons">
                        <!-- Botão Nova Análise Física -->
                        <button class="btn btn-lg btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#modalAnaliseFisica"
                            onclick="carregarGraficosExtras(document.getElementById('aluno').value)">
                            <i class="bi bi-clipboard2-pulse"></i>
                        </button>

                        <!-- Botão Saúde do Atleta -->
                        <button class="btn btn-lg btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#modalSaudeAtleta"
                            onclick="carregarGraficosExtras(document.getElementById('aluno').value)">
                            <i class="bi bi-clipboard2-heart"></i>
                        </button>

                        <!-- Botão Linha do Tempo -->
                        <button class="btn btn-lg btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#modalTimeline"
                            onclick="carregarTimeline(document.getElementById('aluno').value)">
                            <i class="bi bi-clock-history"></i>
                        </button>
                    </div>
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

    {{-- Modal: Linha do Tempo --}}
    <div class="modal fade" id="modalTimeline" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Linha do Tempo do Atleta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="overlay-timeline" class="overlay-spinner d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <div id="timeline-empty" class="text-center my-4 d-none">
                        <p class="text-muted">Nenhum evento encontrado para este atleta.</p>
                    </div>

                    <div id="timeline-container">
                        {{-- O JS irá injetar aqui o conteúdo agrupado por ano/mês --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Detalhes do Evento --}}
    <div class="modal fade" id="modalEventoDetalhes" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="overlay-evento" class="overlay-spinner d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <div id="detalhes-conteudo">
                        {{-- Conteúdo injetado pelo JS --}}
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
            // Linha do tempo
            // ------------------------------------------------

            const baseURL = window.location.origin;
            const tplTimelineBase = `${baseURL}/analise/timeline`; // /analise/timeline/{matricula}
            const tplEventBase = `${baseURL}/analise/timeline/event`; // /analise/timeline/event/{id}

            // Formata data ISO para DD/MM/YYYY HH:mm:ss
            function formatDateBR(iso) {
                const d = new Date(iso);
                const pad = n => String(n).padStart(2, '0');
                return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
            }

            // Renderiza rótulo do item: Criado ou Atualizado + data
            function labelEvento(evento, iso) {
                const formatted = formatDateBR(iso);

                // Criação do atleta
                if (evento === 'created') {
                    return `Criado - ${formatted}`;
                }

                // Criação de análise (novo conjunto de atributos)
                if (evento === 'analise_created' || evento === 'analise_create') {
                    return `Atualizado - ${formatted}`;
                }

                // Atualizações explícitas (ajuste conforme seus nomes reais no DB)
                const updateEvents = ['updated', 'analise_updated', 'analise_update'];
                if (updateEvents.includes(evento)) {
                    return `Atualizado - ${formatted}`;
                }

                // Fallback: se o evento contiver 'update' ou 'alter' tratamos como atualizado
                if (String(evento).toLowerCase().includes('update') || String(evento).toLowerCase().includes(
                        'alter')) {
                    return `Atualizado - ${formatted}`;
                }

                return `${evento} - ${formatted}`;
            }

            // carregar timeline
            window.carregarTimeline = function(matricula) {
                const overlay = document.getElementById('overlay-timeline');
                const container = document.getElementById('timeline-container');
                const empty = document.getElementById('timeline-empty');

                if (!matricula) {
                    container.innerHTML = '';
                    empty.classList.remove('d-none');
                    return;
                }

                overlay.classList.remove('d-none');
                container.innerHTML = '';
                empty.classList.add('d-none');

                const url = `${tplTimelineBase}/${encodeURIComponent(matricula)}`;

                fetch(url)
                    .then(r => {
                        if (!r.ok) throw new Error('Fetch error');
                        return r.json();
                    })
                    .then(json => {
                        overlay.classList.add('d-none');
                        const events = json.events || [];

                        if (!events.length) {
                            empty.classList.remove('d-none');
                            return;
                        }

                        // agrupa por mês+ano (ex: Setembro de 2025)
                        const grouped = {};
                        events.forEach(ev => {
                            const dt = new Date(ev.created_at);
                            const monthLabel = dt.toLocaleString('pt-BR', {
                                month: 'long',
                                year: 'numeric'
                            });
                            grouped[monthLabel] = grouped[monthLabel] || [];
                            grouped[monthLabel].push(ev);
                        });

                        // renderizar timeline por mês
                        let html = '';
                        Object.keys(grouped).sort((a, b) => {
                            const toKey = s => new Date(s.split(' de ').reverse().join('-') +
                                '-01');
                            return toKey(b) - toKey(a);
                        }).forEach(monthLabel => {
                            html +=
                                `<h5 class="mt-3">${monthLabel.charAt(0).toUpperCase() + monthLabel.slice(1)}</h5>`;
                            html += '<div class="timeline">';

                            // itens do mês (mais recente primeiro)
                            grouped[monthLabel].forEach((ev, idx) => {
                                const timeLabel = labelEvento(ev.evento, ev.created_at);
                                const user = ev.changed_by ?
                                    `<span class="timeline-user"> — por ${escapeHtml(ev.changed_by)}</span>` :
                                    '';
                                const resumoBreve = ev.evento === 'analise_created' ?
                                    'Atleta Atualizado' : (ev.evento === 'created' ?
                                        'Atleta criado' : 'Evento');

                                const isCreated = (ev.evento === 'created') || (ev
                                    .evento === 'analise_created' && !(ev.dados && ev
                                        .dados.diff && Object.keys(ev.dados.diff).length
                                    ));
                                const hasDiff = ev.dados && ev.dados.diff && typeof ev.dados
                                    .diff === 'object' && Object.keys(ev.dados.diff)
                                    .length > 0;
                                const markerClass = isCreated ? 'marker-created' : (
                                    hasDiff ? 'marker-updated' : 'marker-other');
                                const markerIcon = isCreated ? '★' : (hasDiff ? '✎' : '•');

                                html += `
                      <div class="timeline-item" data-event-id="${ev.id}" data-evento="${ev.evento}">
                        <div class="timeline-marker ${markerClass}" aria-hidden="true"><i>${markerIcon}</i></div>
                        <div class="timeline-content">
                          <div class="timeline-header">
                            <div class="timeline-time">${timeLabel}</div>
                            ${user}
                          </div>
                          <div class="timeline-summary">${escapeHtml(resumoBreve)}</div>
                        </div>
                        <div class="timeline-actions">
                          <button class="btn btn-sm btn-outline-secondary btn-detalhes">Detalhes</button>
                        </div>
                      </div>`;
                            });

                            html += '</div>'; // .timeline
                        });

                        container.innerHTML = html;

                        // adicionar listeners aos botões Detalhes
                        container.querySelectorAll('.timeline-item').forEach(item => {
                            const btn = item.querySelector('.btn-detalhes');
                            const id = item.dataset.eventId;
                            const evento = item.dataset.evento;
                            if (btn) {
                                btn.addEventListener('click', () => window.verDetalhesEvento(id,
                                    evento));
                            }
                        });
                    })
                    .catch(() => {
                        overlay.classList.add('d-none');
                        container.innerHTML =
                            '<div class="alert alert-danger">Erro ao carregar a linha do tempo.</div>';
                    });
            };

            // Carrega detalhes do evento e exibe modal com conteúdo específico
            window.verDetalhesEvento = function(id, evento) {
                const overlay = document.getElementById('overlay-evento');
                const conteudo = document.getElementById('detalhes-conteudo');
                const modalEl = new bootstrap.Modal(document.getElementById('modalEventoDetalhes'));

                overlay.classList.remove('d-none');
                conteudo.innerHTML = '';

                fetch(`${tplEventBase}/${encodeURIComponent(id)}`)
                    .then(r => {
                        if (!r.ok) throw new Error('Fetch error');
                        return r.json();
                    })
                    .then(json => {

                        overlay.classList.add('d-none');

                        // detectar se existe diff válido
                        const hasDiff = json && json.dados && json.dados.diff && typeof json.dados.diff ===
                            'object' && Object.keys(json.dados.diff).length > 0;

                        // tratar analise_created com diff como 'updated' para exibir somente o diff
                        const effectiveEvento = (evento === 'analise_created' && hasDiff) ? 'updated' :
                            evento;

                        // montar HTML com o evento efetivo
                        const html = buildDetalhesHtml(json, effectiveEvento);
                        conteudo.innerHTML = html;

                        modalEl.show();
                    })
                    .catch(() => {
                        overlay.classList.add('d-none');
                        conteudo.innerHTML =
                            '<div class="alert alert-danger">Erro ao carregar detalhes do evento.</div>';
                        modalEl.show();
                    });

            };

            // Constrói o HTML do modal com prioridade para dados.diff quando presente
            function buildDetalhesHtml(json, evento) {
                const createdInfo =
                    `<div class="mb-2"><strong>Data:</strong> ${formatDateBR(json.created_at)} ${json.changed_by ? ' — por ' + escapeHtml(json.changed_by) : ''}</div>`;
                let html = createdInfo;
                const d = json.dados || {};
                if (!d || typeof d !== 'object') {
                    html += '<div class="text-muted">Nenhum dado disponível.</div>';
                    return html;
                }

                // Se existir diff: filtrar entradas onde antes !== depois (comparação tolerante) e renderizar apenas as mudanças reais
                if (d && d.diff && typeof d.diff === 'object' && Object.keys(d.diff).length) {
                    // normaliza um valor para comparação (null/undefined ficam null)
                    const normalize = v => {
                        if (v === null || v === undefined) return null;
                        if (typeof v === 'boolean') return v;
                        // tenta converter string numérica para número para comparação numérica
                        if (typeof v === 'string' && v !== '' && !isNaN(v)) return Number(v);
                        return v;
                    };

                    // retorna true se valores realmente diferentes
                    const changed = (a, b) => {
                        const na = normalize(a);
                        const nb = normalize(b);
                        // tratamento especial: null vs '' considera diferente
                        if (na === null && nb === null) return false;
                        if (typeof na === 'number' && typeof nb === 'number') return na !== nb;
                        if (typeof na === 'boolean' || typeof nb === 'boolean') return na !== nb;
                        // fallback string compare
                        return String(na) !== String(nb);
                    };

                    // construir um diff filtrado contendo apenas campos que mudaram
                    const filteredDiff = {};
                    Object.entries(d.diff).forEach(([key, value]) => {
                        // caso value seja um campo direto {antes,depois}
                        if (value && typeof value === 'object' && ('antes' in value || 'depois' in value)) {
                            if (changed(value.antes, value.depois)) {
                                filteredDiff[key] = value;
                            }
                            return;
                        }

                        // caso value seja grupo (tecnicos, fisicos, etc)
                        if (value && typeof value === 'object') {
                            const groupObj = {};
                            Object.entries(value).forEach(([campo, vals]) => {
                                // vals esperado {antes,depois} ou pode ter outras formas; tratar defensivamente
                                const antes = vals && (vals.antes !== undefined) ? vals.antes : (d
                                    .antes && d.antes[campo] !== undefined ? d.antes[campo] :
                                    null);
                                const depois = vals && (vals.depois !== undefined) ? vals.depois : (
                                    d.depois && d.depois[campo] !== undefined ? d.depois[
                                        campo] : null);
                                if (changed(antes, depois)) {
                                    groupObj[campo] = {
                                        antes: antes === undefined ? null : antes,
                                        depois: depois === undefined ? null : depois
                                    };
                                }
                            });
                            if (Object.keys(groupObj).length) filteredDiff[key] = groupObj;
                            return;
                        }
                    });

                    // se após filtragem não houver mudanças reais, sair (mostrar fallback/payload)
                    if (!Object.keys(filteredDiff).length) {
                        // cai para o comportamento sem diff (primeira análise ou payload completo)
                    } else {
                        html += '<h6>Campos alterados</h6><ul class="list-group mb-2">';
                        Object.entries(filteredDiff).forEach(([key, value]) => {
                            // campo direto
                            if (value && typeof value === 'object' && ('antes' in value || 'depois' in
                                    value)) {
                                const antes = value.antes === null || value.antes === undefined ? '—' :
                                    escapeHtml(String(value.antes));
                                const depois = value.depois === null || value.depois === undefined ? '—' :
                                    escapeHtml(String(value.depois));
                                html += `<li class="list-group-item">
                            <div class="fw-semibold">${escapeHtml(key)}</div>
                            <div class="small text-muted">Antes: <code>${antes}</code></div>
                            <div class="small">Depois: <code>${depois}</code></div>
                         </li>`;
                                return;
                            }

                            // grupo com campos
                            if (value && typeof value === 'object') {
                                html +=
                                    `<li class="list-group-item"><strong>${escapeHtml(key)}</strong><ul class="mt-2">`;
                                Object.entries(value).forEach(([campo, vals]) => {
                                    const antesStr = vals.antes === null || vals.antes ===
                                        undefined ? '—' : escapeHtml(String(vals.antes));
                                    const depoisStr = vals.depois === null || vals.depois ===
                                        undefined ? '—' : escapeHtml(String(vals.depois));
                                    html += `<li class="mb-2">
                                <div class="fw-semibold">${escapeHtml(campo)}</div>
                                <div class="small text-muted">Antes: <code>${antesStr}</code></div>
                                <div class="small">Depois: <code>${depoisStr}</code></div>
                             </li>`;
                                });
                                html += '</ul></li>';
                                return;
                            }
                        });
                        html += '</ul>';
                        return html; // retorna só as mudanças reais
                    }
                }

                // Se NÃO houver diff: comportamentos normais (primeira análise ou outros eventos)
                if ((evento === 'analise_created' || evento === 'create' || evento === 'created') && (d.tecnicos ||
                        d.fisicos || d.composicao || d.saude)) {
                    if (d.tecnicos) {
                        html += '<h6>Técnicos</h6><ul class="list-group mb-2">';
                        Object.entries(d.tecnicos).forEach(([k, v]) => {
                            html +=
                                `<li class="list-group-item d-flex justify-content-between"><span>${escapeHtml(k)}</span><strong>${escapeHtml(String(v))}</strong></li>`;
                        });
                        html += '</ul>';
                    }
                    if (d.fisicos) {
                        html += '<h6>Físicos</h6><ul class="list-group mb-2">';
                        Object.entries(d.fisicos).forEach(([k, v]) => {
                            html +=
                                `<li class="list-group-item d-flex justify-content-between"><span>${escapeHtml(k)}</span><strong>${escapeHtml(String(v))}</strong></li>`;
                        });
                        html += '</ul>';
                    }
                    if (d.composicao) {
                        html += '<h6>Composição Corporal</h6><ul class="list-group mb-2">';
                        Object.entries(d.composicao).forEach(([k, v]) => {
                            html +=
                                `<li class="list-group-item d-flex justify-content-between"><span>${escapeHtml(k)}</span><strong>${escapeHtml(String(v))}</strong></li>`;
                        });
                        html += '</ul>';
                    }
                    if (d.saude) {
                        html += '<h6>Saúde</h6><ul class="list-group mb-2">';
                        Object.entries(d.saude).forEach(([k, v]) => {
                            html +=
                                `<li class="list-group-item d-flex justify-content-between"><span>${escapeHtml(k)}</span><strong>${escapeHtml(String(v))}</strong></li>`;
                        });
                        html += '</ul>';
                    }
                    return html;
                }

                // evento created de aluno: mostrar nome/matrícula/instituição
                if (evento === 'created' && json.aluno) {
                    const aluno = json.aluno;
                    html += `<dl class="row">
                    <dt class="col-sm-4">Nome</dt><dd class="col-sm-8">${escapeHtml(aluno.nome ?? '—')}</dd>
                    <dt class="col-sm-4">Matrícula</dt><dd class="col-sm-8">${escapeHtml(aluno.matricula ?? '—')}</dd>
                    <dt class="col-sm-4">Instituição</dt><dd class="col-sm-8">${escapeHtml(aluno.instituicao ?? '—')}</dd>
                </dl>`;
                    return html;
                }

                // fallback: mostrar raw pequeno
                html += '<pre class="small bg-light p-2">' + escapeHtml(JSON.stringify(d, null, 2)) + '</pre>';
                return html;
            }

            // helper para ler valores com tolerância a estruturas diferentes
            function safeGetAntesDepois(dDiff, grupo, campo) {
                const fallback = {
                    antes: null,
                    depois: null
                };
                if (!dDiff || typeof dDiff !== 'object') return fallback;

                // aliases possíveis para before/after
                const beforeKeys = ['antes', 'before', 'old', 'old_value', 'previous'];
                const afterKeys = ['depois', 'after', 'new', 'new_value', 'current', 'value'];

                // tenta dDiff[grupo][campo] padrão
                if (dDiff[grupo] && typeof dDiff[grupo] === 'object' && dDiff[grupo][campo]) {
                    const val = dDiff[grupo][campo];
                    if (val && typeof val === 'object') {
                        // procura chaves conhecidas
                        let antes = null,
                            depois = null;
                        for (const k of beforeKeys)
                            if (val[k] !== undefined) {
                                antes = val[k];
                                break;
                            }
                        for (const k of afterKeys)
                            if (val[k] !== undefined) {
                                depois = val[k];
                                break;
                            }
                        // se encontrou ao menos um, retorna
                        if (antes !== null || depois !== null) return {
                            antes: antes ?? null,
                            depois: depois ?? null
                        };
                        // se val é array [antes,depois] ou [depois]
                        if (Array.isArray(val) && val.length) return {
                            antes: val[0] ?? null,
                            depois: val[1] ?? null
                        };
                    }
                }

                // dDiff[campo] sem grupo
                if (dDiff[campo] && typeof dDiff[campo] === 'object') {
                    const val = dDiff[campo];
                    let antes = null,
                        depois = null;
                    for (const k of beforeKeys)
                        if (val[k] !== undefined) {
                            antes = val[k];
                            break;
                        }
                    for (const k of afterKeys)
                        if (val[k] !== undefined) {
                            depois = val[k];
                            break;
                        }
                    if (antes !== null || depois !== null) return {
                        antes: antes ?? null,
                        depois: depois ?? null
                    };
                }

                // checar se dDiff.depois existe e tem estrutura de grupo->campo (caso gravado com 'depois' separado)
                if (dDiff.depois && dDiff.depois[grupo] && dDiff.depois[grupo][campo] !== undefined) {
                    const depois = dDiff.depois[grupo][campo];
                    const antes = (dDiff.antes && dDiff.antes[grupo] && dDiff.antes[grupo][campo] !== undefined) ?
                        dDiff.antes[grupo][campo] : null;
                    return {
                        antes: antes ?? null,
                        depois: depois ?? null
                    };
                }

                // procurar nomes alternativos no primeiro nível (ex: tecnicos.arremesso: {old:.., new:..})
                if (dDiff[grupo] && typeof dDiff[grupo] === 'object') {
                    const maybe = dDiff[grupo][campo];
                    if (maybe && typeof maybe === 'object') {
                        for (const k of beforeKeys)
                            if (maybe[k] !== undefined) {
                                return {
                                    antes: maybe[k],
                                    depois: maybe[afterKeys.find(x => maybe[x] !== undefined)] ?? null
                                };
                            }
                    }
                }

                return fallback;
            }

            // helper simples para escapar conteúdo antes de injetar no DOM
            function escapeHtml(s) {
                if (s === null || s === undefined) return '—';
                if (typeof s !== 'string') return String(s);
                return s.replace(/[&<>"'`=\/]/g, function(c) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '/': '&#x2F;',
                    '`': '&#x60;',
                        '=': '&#x3D;'
                    } [c];
                });
            }

            // limpar modal detalhes ao fechar
            document.getElementById('modalEventoDetalhes')?.addEventListener('hidden.bs.modal', () => {
                const conteudo = document.getElementById('detalhes-conteudo');
                if (conteudo) conteudo.innerHTML = '';
                document.getElementById('overlay-evento')?.classList.add('d-none');
            });

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
