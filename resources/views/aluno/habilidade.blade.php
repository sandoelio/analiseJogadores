{{-- resources/views/aluno/habilidade.blade.php --}}
@extends('layouts.app')

@section('title', 'Atualizar Atleta')

@push('styles')
    <style>
        /* Readonly e disabled visuais */
        input[readonly],
        select[disabled] {
            background-color: #e9ecef;
            opacity: 1;
            cursor: not-allowed;
        }

        /* Cores do header e botão */
        .bg-navbar-blue {
            background-color: #28365F !important;
            color: #fff;
        }

        .btn-navbar-blue {
            background-color: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .btn-navbar-blue:hover {
            background-color: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        /* impede scroll horizontal na página */
        html,
        body {
            overflow-x: hidden;
        }

        /* Layout horizontal fixo para perguntas e respostas */
        .saude-item {

            align-items: center;

        }

        .saude-item label {
            margin-bottom: 0;
            font-weight: 500;
            flex: 1;
        }

        .saude-radios {
            display: flex;
            gap: 1rem;
            flex-shrink: 0;
        }

        /* Botões responsivos estáveis */
        .actions-wrap {
            gap: .75rem;
        }

        @media (max-width: 576px) {
            .habilidade-card-body {
                max-height: calc(100vh - 160px);
                overflow-y: auto;
                padding: 1rem;
                box-sizing: border-box;
            }

            .habilidade-card-body .row.g-3 {
                margin-left: 0;
                margin-right: 0;
            }

            .habilidade-card-body .row.g-3>[class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .habilidade-card-body input,
            .habilidade-card-body select,
            .habilidade-card-body .btn {
                width: 100%;
                box-sizing: border-box;
                min-width: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center mt-4 mb-4">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-navbar-blue text-center">
                    <h5 class="mb-0">Atualizar Atleta</h5>
                </div>

                <div class="card-body habilidade-card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('aluno.habilidade.update') }}" method="POST">
                        @csrf

                        {{-- Abas --}}
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#aba1"
                                    role="tab">Identificação</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba2"
                                    role="tab">Habilidades Técnicas</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba3"
                                    role="tab">Atributos Físicos</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba4"
                                    role="tab">Composição Corporal</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba5"
                                    role="tab">Perguntas</a></li>
                        </ul>

                        <div class="tab-content mt-3">
                            {{-- Aba 1: Identificação --}}
                            <div class="tab-pane fade show active" id="aba1" role="tabpanel">
                                <div class="mb-3">
                                    <label for="aluno_select" class="form-label">Selecione o Atleta</label>
                                    <select id="aluno_select" name="aluno_id" class="form-select" required>
                                        <option selected disabled>-- selecione --</option>
                                        @foreach ($alunos as $a)
                                            <option value="{{ $a->id }}">{{ $a->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Aba 2: Habilidades Técnicas --}}
                            <div class="tab-pane fade" id="aba2" role="tabpanel">
                                <div class="row g-3">
                                    @foreach (['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'] as $campo)
                                        <div class="col-6">
                                            <label for="{{ $campo }}" class="form-label">
                                                {{ ucfirst($campo === 'dominio' ? 'Domínio de Bola' : $campo) }}
                                            </label>
                                            <input type="number" id="{{ $campo }}" name="{{ $campo }}"
                                                class="form-control" value="" min="0" max="10" readonly>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Aba 3: Atributos Físicos --}}
                            <div class="tab-pane fade" id="aba3" role="tabpanel">
                                <div class="row g-3">
                                    @foreach ([
            'envergadura' => 'Envergadura (cm)',
            'velocidade' => 'Velocidade (s)',
            'agilidade' => 'Agilidade (s)',
            'salto_horizontal' => 'Salto Horizontal (cm)',
            'resistencia' => 'Resistência (%)',
        ] as $campo => $label)
                                        <div class="col-6">
                                            <label for="{{ $campo }}" class="form-label">{{ $label }}</label>
                                            <input type="number" id="{{ $campo }}" name="{{ $campo }}"
                                                class="form-control" value="" min="0" max="100" readonly>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Aba 4: Composição Corporal --}}
                            <div class="tab-pane fade" id="aba4" role="tabpanel">
                                <div class="row g-3">
                                    @foreach ([
            'massa_magra_kg' => 'Massa Magra (kg)',
            'massa_adiposa_kg' => 'Massa Adiposa (kg)',
            'massa_magra_pct' => 'Massa Magra (%)',
            'massa_adiposa_pct' => 'Massa Adiposa (%)',
            'peso_residual_kg' => 'Peso Residual (kg)',
        ] as $campo => $label)
                                        <div class="col-6">
                                            <label for="{{ $campo }}"
                                                class="form-label">{{ $label }}</label>
                                            <input type="number" id="{{ $campo }}" name="{{ $campo }}"
                                                class="form-control" value="" min="0" max="100" readonly>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Aba 5: Perguntas --}}
                            <div class="tab-pane fade" id="aba5" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12 mt-2">
                                        <h6 class="text-primary">Informações de Saúde</h6>
                                    </div>

                                    @php
                                        $saudeCampos = [
                                            'problema_saude' => 'Possui problema de saúde?',
                                            'atestado_valido' => 'Está com atestado válido?',
                                            'usa_medicacao' => 'Faz uso de medicação?',
                                        ];
                                    @endphp

                                    @foreach ($saudeCampos as $campo => $label)
                                        <div class="col-12 saude-item">
                                            <label for="{{ $campo }}_sim">{{ $label }}</label>
                                            <div class="saude-radios">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="{{ $campo }}" id="{{ $campo }}_sim"
                                                        value="1" disabled>
                                                    <label class="form-check-label"
                                                        for="{{ $campo }}_sim">Sim</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="{{ $campo }}" id="{{ $campo }}_nao"
                                                        value="0" disabled>
                                                    <label class="form-check-label"
                                                        for="{{ $campo }}_nao">Não</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Botões --}}
                        <div class="mt-4">
                            <div class="d-grid d-md-flex justify-content-md-between actions-wrap">
                                <button type="submit" class="btn btn-navbar-blue flex-md-grow-1">Atualizar
                                    Atleta</button>
                                <a href="{{ route('tecnico.dashboard') }}"
                                    class="btn btn-secondary flex-md-grow-1">Cancelar</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('aluno_select');
            if (!select) return;

            const tecnicos = ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'];
            const fisicos = ['envergadura', 'velocidade', 'agilidade', 'salto_horizontal', 'resistencia'];
            const composicao = ['massa_magra_kg', 'massa_adiposa_kg', 'massa_magra_pct', 'massa_adiposa_pct',
                'peso_residual_kg'
            ];
            const saude = ['problema_saude', 'atestado_valido', 'usa_medicacao'];

            function setReadonly(ids, ro = true) {
                ids.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        if (ro) el.setAttribute('readonly', 'readonly');
                        else el.removeAttribute('readonly');
                    }
                });
            }

            function setDisabledRadios(names, dis = true) {
                names.forEach(name => {
                    const radios = document.querySelectorAll(`input[name="${name}"]`);
                    radios.forEach(r => r.disabled = dis);
                });
            }

            // Inicial: tudo bloqueado
            setReadonly([...tecnicos, ...fisicos, ...composicao], true);
            setDisabledRadios(saude, true);

            select.addEventListener('change', () => {
                const id = select.value;
                if (!id) return;

                fetch(`{{ url('/aluno') }}/${id}/ultima-analise`)
                    .then(res => res.json())
                    .then(data => {
                        // Preenche e libera técnicos
                        tecnicos.forEach(campo => {
                            const el = document.getElementById(campo);
                            if (el) {
                                el.value = data?.[campo] ?? '';
                                el.removeAttribute('readonly');
                            }
                        });

                        // Preenche e libera físicos
                        fisicos.forEach(campo => {
                            const el = document.getElementById(campo);
                            if (el) {
                                el.value = data?.[campo] ?? '';
                                el.removeAttribute('readonly');
                            }
                        });

                        // Preenche e libera composição
                        composicao.forEach(campo => {
                            const el = document.getElementById(campo);
                            if (el) {
                                el.value = data?.[campo] ?? '';
                                el.removeAttribute('readonly');
                            }
                        });

                        // Preenche e libera saúde (radios)
                        setDisabledRadios(saude, false);
                        saude.forEach(name => {
                            const val = data?.[name];
                            const sim = document.getElementById(`${name}_sim`);
                            const nao = document.getElementById(`${name}_nao`);
                            if (sim && nao) {
                                if (val === 1 || val === '1' || val === true) {
                                    sim.checked = true;
                                    nao.checked = false;
                                } else if (val === 0 || val === '0' || val === false) {
                                    sim.checked = false;
                                    nao.checked = true;
                                } else {
                                    sim.checked = false;
                                    nao.checked = false;
                                }
                            }
                        });
                    })
                    .catch(() => alert('Não foi possível carregar a última análise.'));
            });
        });
    </script>
@endpush
