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

        .field-wrapper,
        .chart-wrapper {
            position: relative;
        }

        .back-logo {
            background: #28365F;
            display: block;
            margin: 0 auto 0.4rem;
            max-width: 200px;
            width: 100%;
            height: auto;
        }

        .field-wrapper .form-select {
            display: block;
            margin: 0 auto;
            max-width: 600px;
            width: 100%;
        }

        .volver-wrapper .btn {
            max-width: 200px;
            width: 100%;
            margin: 0 auto;
            display: block;
            background: #28365F;
            margin-top: 5px;
        }

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

        .marker-created {
            background: #28a745;
        }

        .marker-updated {
            background: #0d6efd;
        }

        .marker-other {
            background: #6c757d;
        }

        .timeline-marker i {
            font-style: normal;
            font-size: 12px;
        }

        .timeline-content {
            margin-left: 44px;
            width: 100%;
        }

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

        .timeline-summary {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 6px;
        }

        .timeline-actions {
            margin-left: 12px;
            flex-shrink: 0;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Modal Timeline */
        #modalTimeline .modal-content {
            background: linear-gradient(180deg, #f0f8ff 0%, #ffffff 100%);
            color: #0d2433;
            border: 1px solid rgba(13, 37, 51, 0.06);
            box-shadow: 0 8px 24px rgba(13, 37, 51, 0.08);
        }

        /* Modal Detalhes */
        #modalEventoDetalhes .modal-content {
            background: linear-gradient(180deg, #fffaf0 0%, #ffffff 100%);
            color: #342a1a;
            border: 1px solid rgba(52, 42, 26, 0.06);
            box-shadow: 0 8px 24px rgba(52, 42, 26, 0.06);
        }

        #modalTimeline .modal-header,
        #modalEventoDetalhes .modal-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

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

            .action-buttons .btn {
                margin-bottom: 0.5rem;
            }

            .action-buttons.stack-mobile .btn {
                width: 100%;
            }
        }

        @media (min-width: 768px) {

            html,
            body {
                overflow-x: hidden;
            }

            .back-logo {
                margin-top: 1rem;
                margin-bottom: 1.0rem;
            }

            #selecao-container {
                --bs-gutter-y: .5rem;
                margin-bottom: 0.1rem !important;
            }

            #selecao-container .field-wrapper {
                width: 100% !important;
                /* max-width: 600px; */
            }

            .chart-wrapper {
                max-width: 600px;
                margin: 0.5rem auto 2rem;
            }
        }
    </style>
@endpush

<script>
    const APP_BASE = "{{ rtrim(url('/'), '/') }}";
    const tplEventBase = `${APP_BASE}/analise/timeline/event`;
    const tplTimelineBase = `${APP_BASE}/analise/timeline`;
</script>

@section('content')
    @php
        $user = auth()->user();
        $isAdmin = auth()->check() && (int) ($user->is_admin ?? 0) === 1;

        // Para não-admin: instituição efetiva (sessão do atleta ou instituição do técnico)
        $instituicaoId = $isAdmin
            ? null
            : session('aluno_instituicao_id') ?? (auth()->check() ? $user->instituicao_id ?? null : null);

        // Privilegiado = técnico/admin (atleta não)
        $isPrivilegiado = auth()->check() && !session()->has('aluno_instituicao_id');
    @endphp

    <div class="container-fluid">

        {{-- Voltar --}}
        <div class="row justify-content-center mb-2 mt-0 volver-wrapper">
            <div class="col-12 col-md-6 text-center">
                <a href="{{ route('public.dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house-door me-1"></i> Voltar
                </a>
            </div>
        </div>

        {{-- SELEÇÃO --}}
        <div id="selecao-container" class="row gx-1 gy-1 justify-content-center mb-0">

            @if ($isAdmin)
                {{-- ADMIN: Instituição + Atleta --}}
                <div class="col-12 col-md-12 position-relative field-wrapper admin-stack">
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

                <div class="col-12 col-md-12 position-relative field-wrapper mt-2 admin-stack">
                    <select id="aluno" class="form-select" disabled>
                        <option selected disabled>Selecione o atleta</option>
                    </select>
                    <div id="overlay-aluno" class="overlay-spinner d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <small id="aluno_nome_display" class="d-block text-secondary"></small>
                    <small id="aluno_idade_display" class="d-block text-secondary"></small>
                </div>
            @else
                {{-- ATLETA/TÉCNICO: apenas atletas da própria instituição --}}
                <div class="col-12 col-md-12 position-relative field-wrapper">
                    <select id="aluno" class="form-select">
                        <option selected disabled>Carregando atletas…</option>
                    </select>

                    <div id="overlay-aluno" class="overlay-spinner d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <small id="aluno_nome_display" class="d-block text-secondary"></small>
                    <small id="aluno_idade_display" class="d-block text-secondary"></small>
                </div>
            @endif
        </div>

        {{-- GRÁFICO --}}
        <div id="estatisticas-container" class="card shadow-sm d-none chart-wrapper">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-bar-chart-fill fs-4 me-2"></i>
                    <h6 class="mb-0">Estatísticas</h6>
                </div>

                @if ($isPrivilegiado)
                    <div class="action-buttons">
                        <button class="btn btn-lg btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#modalAnaliseFisica"
                            onclick="carregarGraficosExtras(document.getElementById('aluno').value)">
                            <i class="bi bi-clipboard2-pulse"></i>
                        </button>

                        <button class="btn btn-lg btn-outline-danger" data-bs-toggle="modal"
                            data-bs-target="#modalSaudeAtleta"
                            onclick="carregarGraficosExtras(document.getElementById('aluno').value)">
                            <i class="bi bi-clipboard2-heart"></i>
                        </button>

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

                        <div id="timeline-container"></div>
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

                        <div id="detalhes-conteudo"></div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

        <script>
            window.IS_ADMIN = @json($isAdmin);
            window.INSTITUICAO_ID = @json($instituicaoId);
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // ------------------------------------------------
                // Referências de elementos
                // ------------------------------------------------
                const selectInst = document.getElementById('instituicao'); // existe apenas pro admin
                const overlayInst = document.getElementById('overlay-instituicao'); // existe apenas pro admin

                const selectAluno = document.getElementById('aluno');
                const overlayAluno = document.getElementById('overlay-aluno');
                const overlayChart = document.getElementById('overlay-chart');

                const statsCont = document.getElementById('estatisticas-container');
                const canvas = document.getElementById('estatisticas-chart');

                let chartInstance = null;

                const tplAlunos = "{{ route('analise.alunos', ['instituicao' => 'INSTITUICAO_ID']) }}";
                const tplShow = "{{ route('analise.mostrar', ['matricula' => 'MATRICULA_ID']) }}";

                // ------------------------------------------------
                // Helpers
                // ------------------------------------------------
                function preencherSelectAlunos(alunos) {
                    selectAluno.innerHTML = '<option selected disabled>Selecione o atleta</option>';

                    alunos.sort((a, b) => {
                        if (a.idade == null && b.idade == null) return 0;
                        if (a.idade == null) return 1;
                        if (b.idade == null) return -1;
                        return a.idade - b.idade;
                    });

                    alunos.forEach(a => {
                        const opt = document.createElement('option');
                        opt.value = a.matricula;
                        opt.textContent = `${a.nome} — ${a.idade !== null ? a.idade + ' anos' : '—'}`;
                        opt.dataset.nome = a.nome;
                        opt.dataset.idade = a.idade;
                        selectAluno.append(opt);
                    });
                }

                function carregarAlunosDaInstituicao(instId, mostrarOverlay = true) {
                    const url = tplAlunos.replace('INSTITUICAO_ID', String(instId));

                    if (mostrarOverlay) overlayAluno?.classList.remove('d-none');

                    selectAluno.disabled = true;
                    selectAluno.innerHTML = '<option selected disabled>Carregando atletas…</option>';

                    return fetch(url, {
                            credentials: 'same-origin'
                        })
                        .then(r => {
                            if (!r.ok) throw new Error('HTTP ' + r.status);
                            return r.json();
                        })
                        .then(alunos => {
                            if (mostrarOverlay) overlayAluno?.classList.add('d-none');
                            selectAluno.disabled = false;
                            preencherSelectAlunos(alunos);
                            statsCont?.classList.add('d-none');
                        })
                        .catch(() => {
                            if (mostrarOverlay) overlayAluno?.classList.add('d-none');
                            selectAluno.disabled = true;
                            selectAluno.innerHTML = '<option selected disabled>Erro ao carregar atletas</option>';
                            alert('Falha ao carregar atletas');
                        });
                }

                // ------------------------------------------------
                // Modo ADMIN: escolhe instituição primeiro
                // ------------------------------------------------
                if (window.IS_ADMIN && selectInst) {
                    selectInst.addEventListener('change', () => {
                        const instId = selectInst.value;
                        if (!instId) return;

                        overlayInst?.classList.remove('d-none');

                        // reset aluno e gráfico
                        selectAluno.disabled = true;
                        selectAluno.innerHTML = '<option selected disabled>Carregando atletas…</option>';
                        statsCont?.classList.add('d-none');

                        carregarAlunosDaInstituicao(selectInst.value, true)
                            .finally(() => overlayInst?.classList.add('d-none'));
                    });
                }

                // ------------------------------------------------
                // Modo TÉCNICO/ATLETA: carrega automático pela instituição efetiva
                // ------------------------------------------------
                else {
                    if (window.INSTITUICAO_ID) {
                        carregarAlunosDaInstituicao(window.INSTITUICAO_ID, false);
                    } else {
                        overlayAluno?.classList.add('d-none');
                        selectAluno.disabled = true;
                        selectAluno.innerHTML =
                            '<option selected disabled>Nenhuma instituição vinculada ao usuário</option>';
                    }
                }

                // ------------------------------------------------
                // Escolheu atleta → mostra gráfico principal
                // ------------------------------------------------
                if (selectAluno) {
                    selectAluno.addEventListener('change', () => {
                        const matricula = selectAluno.value;
                        if (!matricula) return;

                        // overlayAluno?.classList.remove('d-none');
                        overlayChart?.classList.remove('d-none');
                        statsCont?.classList.remove('d-none');
                        if (canvas) canvas.style.display = 'none';

                        fetch(tplShow.replace('MATRICULA_ID', matricula), {
                                credentials: 'same-origin'
                            })
                            .then(r => {
                                if (!r.ok) throw new Error('HTTP ' + r.status);
                                return r.json();
                            })
                            .then(data => {
                                // overlayAluno?.classList.add('d-none');
                                overlayChart?.classList.add('d-none');
                                if (canvas) canvas.style.display = '';

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
                                            },
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
                                overlayAluno?.classList.add('d-none');
                                overlayChart?.classList.add('d-none');
                                alert('Não foi possível carregar o gráfico.');
                            });
                    });
                }

                // ------------------------------------------------
                // Gráficos extras (modais)
                // ------------------------------------------------
                let graficoFisicoInstance = null;
                let graficoClinicoInstance = null;

                window.carregarGraficosExtras = function(matricula) {
                    if (!matricula) return;

                    const url = `${APP_BASE}/analise/extras/${encodeURIComponent(matricula)}`;
                    fetch(url, {
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('HTTP ' + response.status);
                            return response.json();
                        })
                        .then(data => {
                            if (!data || !data.fisico || !data.clinico) throw new Error('Payload inválido');

                            const ctxFisicoEl = document.getElementById('graficoFisico');
                            if (ctxFisicoEl) {
                                const ctxFisico = ctxFisicoEl.getContext('2d');
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
                                            },
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: v => Number.isInteger(v) ? v : ''
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
                            }

                            const ctxClinicoEl = document.getElementById('graficoClinico');
                            if (ctxClinicoEl) {
                                const ctxClinico = ctxClinicoEl.getContext('2d');
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
                                            },
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: v => Number.isInteger(v) ? v : ''
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
                            }

                            const classificacao = document.getElementById('classificacaoLabel');
                            if (classificacao && data.classificacao !== undefined) {
                                classificacao.textContent = data.classificacao;
                                classificacao.classList.remove('d-none');
                            }
                        })
                        .catch(() => {
                            alert('Erro ao carregar dados físicos e clínicos.');
                        });
                };

                // ------------------------------------------------
                // Timeline
                // ------------------------------------------------
                function formatDateBR(iso) {
                    const d = new Date(iso);
                    const pad = n => String(n).padStart(2, '0');
                    return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
                }

                function labelEvento(evento, iso) {
                    const formatted = formatDateBR(iso);
                    if (evento === 'created') return `Criado - ${formatted}`;
                    if (evento === 'analise_created' || evento === 'analise_create') return `Atualizado - ${formatted}`;
                    const updateEvents = ['updated', 'analise_updated', 'analise_update'];
                    if (updateEvents.includes(evento)) return `Atualizado - ${formatted}`;
                    const evLower = String(evento).toLowerCase();
                    if (evLower.includes('update') || evLower.includes('alter')) return `Atualizado - ${formatted}`;
                    return `${evento} - ${formatted}`;
                }

                window.carregarTimeline = function(matricula) {
                    const overlay = document.getElementById('overlay-timeline');
                    const container = document.getElementById('timeline-container');
                    const empty = document.getElementById('timeline-empty');

                    if (!matricula) {
                        if (container) container.innerHTML = '';
                        if (empty) empty.classList.remove('d-none');
                        return;
                    }

                    overlay?.classList.remove('d-none');
                    if (container) container.innerHTML = '';
                    empty?.classList.add('d-none');

                    const url = `${tplTimelineBase}/${encodeURIComponent(matricula)}`;

                    fetch(url, {
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('HTTP ' + response.status);
                            return response.json();
                        })
                        .then(json => {
                            overlay?.classList.add('d-none');
                            const events = (json && Array.isArray(json.events)) ? json.events : [];

                            if (!events.length) {
                                empty?.classList.remove('d-none');
                                return;
                            }

                            const grouped = {};
                            events.forEach(ev => {
                                const dt = new Date(ev.created_at);
                                const monthLabel = dt.toLocaleString('pt-BR', {
                                    month: 'long',
                                    year: 'numeric'
                                });
                                (grouped[monthLabel] = grouped[monthLabel] || []).push(ev);
                            });

                            let html = '';
                            Object.keys(grouped).sort((a, b) => {
                                const toKey = s => new Date(s.split(' de ').reverse().join('-') +
                                    '-01');
                                return toKey(b) - toKey(a);
                            }).forEach(monthLabel => {
                                const title = monthLabel.charAt(0).toUpperCase() + monthLabel.slice(1);
                                html += `<h5 class="mt-3">${title}</h5>`;
                                html += '<div class="timeline">';

                                grouped[monthLabel].forEach(ev => {
                                    const timeLabel = labelEvento(ev.evento, ev.created_at);
                                    const user = ev.changed_by ?
                                        `<span class="timeline-user"> — por ${escapeHtml(ev.changed_by)}</span>` :
                                        '';
                                    const resumoBreve = ev.evento === 'analise_created' ?
                                        'Atleta Atualizado' :
                                        (ev.evento === 'created' ? 'Atleta criado' : 'Evento');

                                    const isCreated = (ev.evento === 'created') ||
                                        (ev.evento === 'analise_created' && !(ev.dados && ev
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

                                html += '</div>';
                            });

                            if (container) container.innerHTML = html;

                            container?.querySelectorAll('.timeline-item').forEach(item => {
                                const btn = item.querySelector('.btn-detalhes');
                                const id = item.dataset.eventId;
                                const evento = item.dataset.evento;
                                btn?.addEventListener('click', () => window.verDetalhesEvento(id,
                                    evento));
                            });
                        })
                        .catch(() => {
                            overlay?.classList.add('d-none');
                            if (container) container.innerHTML =
                                '<div class="alert alert-danger">Erro ao carregar a linha do tempo.</div>';
                        });
                };

                window.verDetalhesEvento = function(id, evento) {
                    const overlay = document.getElementById('overlay-evento');
                    const conteudo = document.getElementById('detalhes-conteudo');
                    const modalEl = new bootstrap.Modal(document.getElementById('modalEventoDetalhes'));

                    overlay?.classList.remove('d-none');
                    if (conteudo) conteudo.innerHTML = '';

                    const url = `${tplEventBase}/${encodeURIComponent(id)}`;

                    fetch(url, {
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('HTTP ' + response.status);
                            return response.json();
                        })
                        .then(json => {
                            overlay?.classList.add('d-none');

                            const hasDiff = json && json.dados && json.dados.diff && typeof json.dados.diff ===
                                'object' &&
                                Object.keys(json.dados.diff).length > 0;

                            const effectiveEvento = (evento === 'analise_created' && hasDiff) ? 'updated' :
                                evento;

                            const html = buildDetalhesHtml(json, effectiveEvento);
                            if (conteudo) conteudo.innerHTML = html;

                            modalEl.show();
                        })
                        .catch(() => {
                            overlay?.classList.add('d-none');
                            if (conteudo) conteudo.innerHTML =
                                '<div class="alert alert-danger">Erro ao carregar detalhes do evento.</div>';
                            modalEl.show();
                        });
                };

                function buildDetalhesHtml(json, evento) {
                    const createdInfo =
                        `<div class="mb-2"><strong>Data:</strong> ${formatDateBR(json.created_at)} ${json.changed_by ? ' — por ' + escapeHtml(json.changed_by) : ''}</div>`;
                    let html = createdInfo;

                    const d = json.dados || {};
                    if (!d || typeof d !== 'object') {
                        html += '<div class="text-muted">Nenhum dado disponível.</div>';
                        return html;
                    }

                    // fallback simples (mantém compatibilidade)
                    html += '<pre class="small bg-light p-2">' + escapeHtml(JSON.stringify(d, null, 2)) + '</pre>';
                    return html;
                }

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

                document.getElementById('modalEventoDetalhes')?.addEventListener('hidden.bs.modal', () => {
                    const conteudo = document.getElementById('detalhes-conteudo');
                    if (conteudo) conteudo.innerHTML = '';
                    document.getElementById('overlay-evento')?.classList.add('d-none');
                });

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
                // ✅ Anti-fantasma: overlay nunca aparece por foco/click
                function hideAllOverlays() {
                    document.querySelectorAll('.overlay-spinner').forEach(el => el.classList.add('d-none'));
                }

                // ao clicar/focar em qualquer select, garanta que overlay está escondido
                document.querySelectorAll('select.form-select').forEach(sel => {
                    sel.addEventListener('focus', hideAllOverlays);
                    sel.addEventListener('click', hideAllOverlays);
                    sel.addEventListener('mousedown', hideAllOverlays);
                });
            });
        </script>
    @endpush
