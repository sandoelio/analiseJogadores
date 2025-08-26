{{-- resources/views/analise/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise individual')

@push('styles')
    <style>
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

        /* wrapper relative para selects e chart */
        .field-wrapper,
        .chart-wrapper {
            position: relative;
        }

        /* estiliza o spinner maior */
        .overlay-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        /* reduz e centraliza a logo */
        .back-logo {
            background: #28365F;
            display: block;
            margin: 0 auto 1.5rem;
            max-width: 200px;
            /* <– ajuste pra largura desejada */
            width: 100%;
            height: auto;
        }

        /* limita a largura dos selects e centraliza */
        .field-wrapper .form-select {
            display: block;
            margin: 0 auto;
            max-width: 600px;
            /* <– ajuste pra largura desejada */
            width: 100%;
        }

        /* opcional: limita também o botão “Voltar” */
        .volver-wrapper .btn {
            max-width: 200px;
            width: 200%;
            margin: 0 auto;
            display: block;
            background: #28365F
        }

        #estatisticas-chart {
            width: 100% !important;
            height: auto !important;
            max-width: 650px;
            min-height: 300px;
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
        <div class="row justify-content-center mb-4 volver-wrapper">
            <div class="col-12 col-md-6 text-center">
                <a href="{{ route('public.dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-house-door me-1"></i> Voltar
                </a>
            </div>
        </div>


        {{-- SELEÇÃO --}}
        <div id="selecao-container" class="row gx-3 gy-3 justify-content-center mb-5">
            @if ($atletaInst)
                {{-- Usuário atleta: só seleciona o próprio atleta --}}
                <div class="col-12 col-md-6 position-relative field-wrapper">
                    {{-- <label for="aluno" class="form-label">
                        <i class="bi bi-person-badge me-1"></i>Atleta
                    </label> --}}
                    <select id="aluno" class="form-select">
                        <option selected disabled>Carregando atletas…</option>
                    </select>
                    <div id="overlay-aluno" class="overlay-spinner">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            @else
                {{-- Público/Admin/Técnico: escolhe instituição e depois atleta --}}
                <div id="instituicao-wrapper" class="col-12 col-md-6 position-relative field-wrapper">
                    <label for="instituicao" class="form-label">
                        <i class="bi bi-building me-1"></i>Instituição
                    </label>
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
                <div id="aluno-wrapper" class="col-12 col-md-6 d-none position-relative field-wrapper">
                    <label for="aluno" class="form-label">
                        <i class="bi bi-person-badge me-1"></i>Atleta
                    </label>
                    <select id="aluno" class="form-select">
                        <option selected disabled>Selecione um atleta</option>
                    </select>
                    <div id="overlay-aluno" class="overlay-spinner d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- GRÁFICO --}}
        <div id="estatisticas-container" class="card shadow-sm d-none chart-wrapper">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-bar-chart-fill fs-4 me-2"></i>
                <h5 class="mb-0">Estatísticas do Atleta</h5>
            </div>
            <div class="card-body p-3 d-flex justify-content-center" style="min-height:320px;position:relative;">
                <div id="overlay-chart" class="overlay-spinner d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <canvas id="estatisticas-chart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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

            // Se jogador está logado, já dispara fetch de atletas da própria instituição
            @if ($atletaInst)
                overlayAluno.classList.remove('d-none');
                fetch(tplAlunos.replace('INSTITUICAO_ID', "{{ $atletaInst }}"))
                    .then(r => r.json())
                    .then(alunos => {
                        overlayAluno.classList.add('d-none');
                        selectAluno.innerHTML = '<option selected disabled>Selecione um atleta</option>';
                        alunos.forEach(a => selectAluno.append(new Option(a.nome, a.matricula)));
                    })
                    .catch(() => {
                        overlayAluno.classList.add('d-none');
                        alert('Falha ao carregar atletas');
                    });
            @endif

            // Se selecionou instituição (público ou técnico), carrega atletas
            if (selectInst) {
                selectInst.addEventListener('change', () => {
                    overlayInst.classList.remove('d-none');
                    fetch(tplAlunos.replace('INSTITUICAO_ID', selectInst.value))
                        .then(r => r.json())
                        .then(alunos => {
                            overlayInst.classList.add('d-none');
                            document.getElementById('aluno-wrapper').classList.remove('d-none');
                            selectAluno.innerHTML =
                                '<option selected disabled>Selecione um atleta</option>';
                            alunos.forEach(a => selectAluno.append(new Option(a.nome, a.matricula)));
                            statsCont.classList.add('d-none');
                        })
                        .catch(() => overlayInst.classList.add('d-none'));
                });
            }

            // Ao escolher atleta, exibe o gráfico
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
                                        backgroundColor: 'rgba(255,159,64,0.8)',
                                        borderRadius: 4,
                                        maxBarThickness: 50
                                    },
                                    {
                                        label: 'Atual',
                                        data: data.atual,
                                        backgroundColor: 'rgba(54,162,235,0.8)',
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
        });
    </script>
@endpush

