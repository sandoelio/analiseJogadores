@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
    <style>
        .create-shell {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0.95rem 0 1.2rem;
        }

        .create-topo {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .create-heading,
        .create-voltar-wrap,
        .create-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .create-heading {
            flex: 1 1 auto;
            padding: 1rem 1.1rem;
        }

        .create-heading-top {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .create-chip {
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

        .create-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.46rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .create-text {
            margin: 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .create-voltar-wrap {
            flex: 0 0 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem;
        }

        .create-voltar {
            width: 100%;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .create-card {
            overflow: hidden;
        }

        .create-card .card-header {
            padding: 1rem 1.1rem;
            background: linear-gradient(135deg, #28365F 0%, #40548c 100%) !important;
            color: #fff;
        }

        .create-card .card-header h5 {
            font-size: 1.08rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .create-card .card-body {
            padding: 1.15rem;
        }

        .create-card .form-label {
            color: #33405f;
            font-weight: 600;
        }

        .create-card .form-control,
        .create-card .form-select {
            min-height: 46px;
            border-radius: 0.8rem;
            border-color: #dbe1ec;
            box-shadow: none;
        }

        .create-card .form-control:focus,
        .create-card .form-select:focus {
            border-color: #8ea3ce;
            box-shadow: 0 0 0 0.2rem rgba(40, 54, 95, 0.12);
        }

        .create-card .form-check-input:checked {
            background-color: #28365F;
            border-color: #28365F;
        }

        .create-card .nav-tabs {
            border-bottom: 1px solid #dbe1ec;
            gap: 0.35rem;
        }

        .create-card .nav-tabs .nav-link {
            border: 1px solid #dbe1ec;
            border-bottom: none;
            border-top-left-radius: 0.85rem;
            border-top-right-radius: 0.85rem;
            background: #edf2f8;
            color: #2a3b5f;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .create-card .nav-tabs .nav-link.active {
            color: #fff;
            background: #28365F;
            border-color: #28365F;
        }

        .create-card .tab-content {
            padding-top: 0.3rem;
        }

        .create-card .tab-pane {
            min-height: 260px;
        }

        .create-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            margin-top: 1.1rem;
        }

        .create-actions .btn {
            min-height: 44px;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .btn-navbar-blue {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .btn-navbar-blue:hover,
        .btn-navbar-blue:focus {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        input[readonly] {
            background-color: #e9ecef;
            opacity: 1;
            cursor: not-allowed;
        }

        html,
        body {
            overflow-x: hidden;
        }

        .saude-item {
            align-items: center;
            padding: 0.75rem 0.85rem;
            border: 1px solid #e4eaf3;
            border-radius: 0.9rem;
            background: #fbfcfe;
        }

        .saude-item label {
            margin-bottom: 0;
            font-weight: 600;
            flex: 1;
        }

        .saude-radios {
            display: flex;
            gap: 1rem;
            flex-shrink: 0;
        }

        @media (max-width: 576px) {
            .create-shell {
                padding-top: 0.5rem;
            }

            .create-topo {
                flex-direction: column;
                gap: 0.75rem;
            }

            .create-voltar-wrap {
                flex-basis: auto;
                padding: 0.8rem;
            }

            .create-title {
                font-size: 1.2rem;
            }

            .create-card .card-body {
                padding: 0.95rem;
            }

            .create-card .nav-tabs {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0.35rem;
                overflow: visible;
                padding-bottom: 0;
            }

            .create-card .nav-tabs .nav-item {
                width: 100%;
            }

            .create-card .nav-tabs .nav-link {
                width: 100%;
                min-height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.45rem 0.4rem;
                white-space: normal;
                text-align: center;
                font-size: 0.8rem;
                line-height: 1.2;
                border-radius: 0.8rem;
                border-bottom: 1px solid #dbe1ec;
            }

            .create-card .tab-pane {
                min-height: 0;
            }

            .create-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .saude-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.7rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid create-shell">
        <div class="create-topo">
            <div class="create-heading">
                <div class="create-heading-top">
                    <span class="create-chip">
                        <i class="bi bi-person-plus-fill"></i>
                        Cadastro
                    </span>
                    <p class="create-text">
                        Preencha a identificacao e e salve depois em atualizar o atleta e registre as demais informacões.
                    </p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="create-card card mb-4">
                    <div class="card-header text-center">
                        <h5 class="mb-0">Novo Atleta</h5>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div id="success-message"
                                class="alert alert-success alert-dismissible fade show flash-auto flash-floating"
                                data-auto-dismiss="4500" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('aluno.store') }}" method="POST">
                            @csrf

                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                        href="#aba1">Identificacao</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba2">Tecnicas</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba3">Fisicos</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba4">Corporal</a></li>
                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba5">Perguntas</a></li>
                            </ul>

                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="aba1">
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">Nome do Atleta</label>
                                        <input type="text" id="nome" name="nome" placeholder="Nome e sobrenome"
                                            class="form-control @error('nome') is-invalid @enderror"
                                            value="{{ old('nome') }}" required>
                                        @error('nome')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row g-3 align-items-end">
                                        <div class="col-12 col-lg-4">
                                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                            <input type="date" id="data_nascimento" name="data_nascimento"
                                                class="form-control @error('data_nascimento') is-invalid @enderror"
                                                value="{{ old('data_nascimento') }}" required>
                                            @error('data_nascimento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-3">
                                            <label class="form-label">Sexo</label>
                                            <div class="d-flex">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sexo" id="sexo_m"
                                                        value="Masculino"
                                                        {{ old('sexo') == 'Masculino' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="sexo_m">M</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sexo" id="sexo_f"
                                                        value="Feminino" {{ old('sexo') == 'Feminino' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="sexo_f">F</label>
                                                </div>
                                            </div>
                                            @error('sexo')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-2">
                                            <label for="idade_display" class="form-label">Idade</label>
                                            <input type="text" id="idade_display" class="form-control"
                                                value="{{ old('idade') }}" readonly>
                                            <input type="hidden" id="idade" name="idade" value="{{ old('idade') }}">
                                        </div>

                                        <div class="col-12 col-lg-3">
                                            <label for="telefone" class="form-label">Telefone</label>
                                            <input type="text" id="telefone" name="telefone"
                                                placeholder="(00) 00000-0000"
                                                class="form-control @error('telefone') is-invalid @enderror"
                                                value="{{ old('telefone') }}" inputmode="numeric" maxlength="15">
                                            @error('telefone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="aba2">
                                    <div class="row g-3">
                                        @foreach (['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'] as $campo)
                                            <div class="col-6 col-md-6">
                                                <label for="{{ $campo }}" class="form-label">
                                                    {{ ucfirst($campo === 'dominio' ? 'Dominio de Bola' : $campo) }}
                                                </label>
                                                <input type="hidden" name="{{ $campo }}" value="1">
                                                <input type="number" id="{{ $campo }}" class="form-control"
                                                    value="1" min="0" max="100" readonly>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="aba3">
                                    <div class="row g-3">
                                        @foreach ([
        'potencia_mmss' => 'Potencia MMSS',
        'capacidade_aerobica' => 'Capacidade Aerobica',
        'agilidade' => 'Agilidade (s)',
        'flexibilidade' => 'Flexibilidade',
        'potencia_mmii' => 'Potencia MMII',
        'envergadura_cm' => 'Envergadura (cm)',
    ] as $campo => $label)
                                            <div class="col-6 col-md-6">
                                                <label for="{{ $campo }}" class="form-label">{{ $label }}</label>
                                                <input type="hidden" name="{{ $campo }}" value="1">
                                                <input type="number" id="{{ $campo }}" class="form-control"
                                                    value="1" min="0" max="100" readonly>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="aba4">
                                    <div class="row g-3">
                                        @foreach ([
        'massa_corporal_kg' => 'Massa Corporal (kg)',
        'gordura_pct' => 'Gordura (%)',
        'massa_magra_pct' => 'Massa Magra (%)',
        'imc' => 'IMC',
    ] as $campo => $label)
                                            <div class="col-6 col-md-6">
                                                <label for="{{ $campo }}" class="form-label">{{ $label }}</label>
                                                <input type="hidden" name="{{ $campo }}" value="1">
                                                <input type="number" id="{{ $campo }}" class="form-control"
                                                    value="1" min="0" max="100" readonly>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="aba5">
                                    <div class="row g-3">
                                        <div class="col-12 mt-2">
                                            <h6 class="text-primary">Informacoes de Saude</h6>
                                        </div>

                                        @php
                                            $saudeCampos = [
                                                'problema_saude' => 'Possui problema de saude?',
                                                'atestado_valido' => 'Esta com atestado valido?',
                                                'usa_medicacao' => 'Faz uso de medicacao?',
                                            ];
                                        @endphp

                                        @foreach ($saudeCampos as $campo => $label)
                                            <div class="col-12 saude-item">
                                                <label for="{{ $campo }}_sim">{{ $label }}</label>
                                                <div class="saude-radios">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="{{ $campo }}" id="{{ $campo }}_sim"
                                                            value="1" {{ old($campo) === '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="{{ $campo }}_sim">Sim</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="{{ $campo }}" id="{{ $campo }}_nao"
                                                            value="0" {{ old($campo) === '0' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="{{ $campo }}_nao">Nao</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="create-actions">
                                <a href="{{ route('tecnico.dashboard') }}" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-navbar-blue">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataInput = document.getElementById('data_nascimento');
            const idadeDisplay = document.getElementById('idade_display');
            const idadeHidden = document.getElementById('idade');
            const telefoneInput = document.getElementById('telefone');

            function calcularIdade(dataStr) {
                if (!dataStr) return '';
                const hoje = new Date();
                const nasc = new Date(dataStr + 'T00:00:00');
                let idade = hoje.getFullYear() - nasc.getFullYear();
                const m = hoje.getMonth() - nasc.getMonth();
                if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) {
                    idade--;
                }
                return idade >= 0 ? idade : '';
            }

            if (dataInput) {
                const atualizar = function() {
                    const idade = calcularIdade(dataInput.value);
                    idadeDisplay.value = idade !== '' ? idade + '' : '';
                    idadeHidden.value = idade !== '' ? idade : '';
                };

                dataInput.addEventListener('change', atualizar);
                if (dataInput.value) atualizar();
            }

            function aplicarMascaraTelefone(valor) {
                const digitos = valor.replace(/\D/g, '').slice(0, 11);

                if (digitos.length <= 2) {
                    return digitos;
                }

                const padrao = digitos.length > 10 ? /(\d{2})(\d{0,5})(\d{0,4})/ : /(\d{2})(\d{0,4})(\d{0,4})/;

                return digitos.replace(padrao, function(_, ddd, parte1, parte2) {
                    let telefone = '(' + ddd + ')';

                    if (parte1) {
                        telefone += ' ' + parte1;
                    }

                    if (parte2) {
                        telefone += '-' + parte2;
                    }

                    return telefone;
                });
            }

            if (telefoneInput) {
                telefoneInput.addEventListener('input', function() {
                    telefoneInput.value = aplicarMascaraTelefone(telefoneInput.value);
                });

                if (telefoneInput.value) {
                    telefoneInput.value = aplicarMascaraTelefone(telefoneInput.value);
                }
            }

            const successMsg = document.getElementById('success-message');
            if (successMsg) {
                const TIMEOUT = 3000;

                try {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                        successMsg.classList.add('fade', 'show');
                        setTimeout(() => {
                            const bsAlert = bootstrap.Alert.getOrCreateInstance(successMsg);
                            bsAlert.close();
                        }, TIMEOUT);
                        return;
                    }
                } catch (e) {
                }

                successMsg.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    successMsg.style.opacity = '0';
                    setTimeout(() => {
                        if (successMsg.parentNode) successMsg.parentNode.removeChild(successMsg);
                    }, 500);
                }, TIMEOUT);
            }
        });
    </script>
@endpush
