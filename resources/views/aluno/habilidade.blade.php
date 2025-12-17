{{-- resources/views/aluno/habilidade.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise de Desempenhos')

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

                        @php
                            // Ordena em memória: usa coluna 'idade' quando existir, senão calcula por data_nascimento
                            $alunosOrdenados = $alunos->sortByDesc(function ($a) {
                                if (isset($a->idade) && $a->idade !== null) {
                                    return $a->idade;
                                }
                                if (!empty($a->data_nascimento)) {
                                    return \Carbon\Carbon::parse($a->data_nascimento)->age;
                                }
                                return -1; // coloca sem data/idade no final
                            })->values();
                        @endphp
                        <div class="tab-content mt-3">
                                {{-- Aba 1: Identificação --}}
                                <div class="tab-pane fade show active" id="aba1" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="aluno_select" class="form-label">Selecione o Atleta</label>
                                        <select id="aluno_select" name="aluno_id" class="form-select" required>
                                        <option value="" disabled {{ old('aluno_id') ? '' : 'selected' }}>-- selecione --</option>

                                        @foreach ($alunosOrdenados as $a)
                                            @php
                                            $idadeExib = $a->idade ?? (!empty($a->data_nascimento) ? \Carbon\Carbon::parse($a->data_nascimento)->age : null);
                                            $selected = (string) old('aluno_id') === (string) $a->id ? 'selected' : '';
                                            $dataNasc = $a->data_nascimento ? $a->data_nascimento->toDateString() : '';
                                            $sexoVal = $a->sexo ?? '';
                                            $idadeVal = $a->idade ?? ($a->data_nascimento ? \Carbon\Carbon::parse($a->data_nascimento)->age : '');
                                            @endphp

                                            <option
                                            value="{{ $a->id }}"
                                            data-datanascimento="{{ $dataNasc }}"
                                            data-sexo="{{ $sexoVal }}"
                                            data-idade="{{ $idadeVal }}"
                                            {{ $selected }}
                                            >
                                            {{ $a->nome }}@if($idadeExib) ({{ $idadeExib }} anos)@endif
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                            <input
                                                type="date"
                                                id="data_nascimento"
                                                name="data_nascimento"
                                                class="form-control"
                                                value="{{ old('data_nascimento') }}"
                                            >
                                        </div>

                                        <div class="col-3">
                                            <label class="form-label d-block">Sexo</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="sexo" id="sexo_m" value="Masculino">
                                                <label class="form-check-label" for="sexo_m">Masculino</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="sexo" id="sexo_f" value="Feminino">
                                                <label class="form-check-label" for="sexo_f">Feminino</label>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <label class="form-label">Idade</label>
                                            <input type="text" id="idade_display" class="form-control" value="{{ old('idade') }}" readonly>
                                            <input type="hidden" id="idade" name="idade" value="{{ old('idade') }}">
                                        </div>
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
                                                class="form-control" value="" min="0" max="10"
                                                step="1" readonly>
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
                                                class="form-control" value="" min="0" step="any" readonly>
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
                                                class="form-control" value="" min="0" step="any" readonly>
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
        // Base correta da aplicação (inclui /public em produção se necessário)
        const APP_BASE = "{{ rtrim(url('/'), '/') }}";

        // Injeta a rota com placeholder (usa APP_BASE quando necessário apenas para consistência)
        window.routes = {
            lastAnalysis: "{{ route('aluno.lastAnalysis', ['aluno' => '__ID__']) }}"
        };

        document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('aluno_select');
        if (!select) return;

        const tecnicos = ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'];
        const fisicos = ['envergadura', 'velocidade', 'agilidade', 'salto_horizontal', 'resistencia'];
        const composicao = ['massa_magra_kg', 'massa_adiposa_kg', 'massa_magra_pct', 'massa_adiposa_pct', 'peso_residual_kg'];
        const saude = ['problema_saude', 'atestado_valido', 'usa_medicacao'];

        // Identificação
        const dataNascEl = document.getElementById('data_nascimento');
        const sexoMEl = document.getElementById('sexo_m');
        const sexoFEl = document.getElementById('sexo_f');
        const idadeDisplayEl = document.getElementById('idade_display');
        const idadeHiddenEl = document.getElementById('idade');
        const nomeEl = document.getElementById('nome');

        function setReadOnlyByIds(ids, ro = true) {
            ids.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.readOnly = !!ro;
            el.disabled = false;
            });
        }

        function setDisabledRadiosByNames(names, dis = true) {
            names.forEach(name => {
            const radios = document.querySelectorAll(`input[name="${name}"]`);
            radios.forEach(r => {
                r.disabled = !!dis;
                if (dis) r.checked = false;
            });
            });
        }

        // Inicial: bloqueia inputs de análise até seleção
        setReadOnlyByIds([...tecnicos, ...fisicos, ...composicao], true);
        setDisabledRadiosByNames(saude, true);

        // Preenche identificação (nome, data, sexo, idade)
        function preencherIdentificacao(idObj) {
            const id = idObj || {};
            if (nomeEl && (id.nome !== undefined)) {
            nomeEl.value = id.nome ?? '';
            nomeEl.readOnly = false;
            nomeEl.disabled = false;
            }
            if (dataNascEl) dataNascEl.value = id.data_nascimento ?? '';
            const sexoVal = (id.sexo ?? '').toString();
            if (sexoMEl) sexoMEl.checked = (sexoVal === 'M' || sexoVal.toLowerCase() === 'masculino');
            if (sexoFEl) sexoFEl.checked = (sexoVal === 'F' || sexoVal.toLowerCase() === 'feminino');
            if (idadeDisplayEl) {
            if (id.idade !== undefined && id.idade !== null && id.idade !== '') {
                idadeDisplayEl.value = id.idade + ' anos';
                if (idadeHiddenEl) idadeHiddenEl.value = id.idade;
            } else {
                idadeDisplayEl.value = '';
                if (idadeHiddenEl) idadeHiddenEl.value = '';
            }
            }
        }

        // Normaliza payload de análise (aceita top-level ou grupos)
        function normalizeAnalisePayload(payload) {
            if (!payload) return {};

            const allFields = [
            'arremesso','passe','marcacao','bandeja','rebote','dominio',
            'envergadura','velocidade','agilidade','salto_horizontal','resistencia',
            'massa_magra_kg','massa_adiposa_kg','massa_magra_pct','massa_adiposa_pct','peso_residual_kg',
            'problema_saude','atestado_valido','usa_medicacao'
            ];

            // 1) grupos (se existirem)
            const fromGroups = {};
            if (payload.tecnicos || payload.fisicos || payload.composicao || payload.saude) {
            Object.assign(fromGroups, payload.tecnicos || {}, payload.fisicos || {}, payload.composicao || {}, payload.saude || {});
            }

            // 2) campos no topo
            const top = {};
            allFields.forEach(k => {
            if (payload[k] !== undefined) top[k] = payload[k];
            });

            // 3) prioridade: groups > top
            return Object.assign({}, top, fromGroups);
        }

        // Preenche os campos de análise (mantém comportamento original)
        function preencherAnalise(payload) {
            const normalized = normalizeAnalisePayload(payload);

            // técnicos
            tecnicos.forEach(campo => {
            const el = document.getElementById(campo);
            if (!el) return;
            el.value = (normalized[campo] !== undefined && normalized[campo] !== null) ? normalized[campo] : '';
            el.readOnly = false;
            el.disabled = false;
            });

            // físicos
            fisicos.forEach(campo => {
            const el = document.getElementById(campo);
            if (!el) return;
            el.value = (normalized[campo] !== undefined && normalized[campo] !== null) ? normalized[campo] : '';
            el.readOnly = false;
            el.disabled = false;
            });

            // composição
            composicao.forEach(campo => {
            const el = document.getElementById(campo);
            if (!el) return;
            el.value = (normalized[campo] !== undefined && normalized[campo] !== null) ? normalized[campo] : '';
            el.readOnly = false;
            el.disabled = false;
            });

            // saúde (radios)
            setDisabledRadiosByNames(saude, false);
            saude.forEach(name => {
            const val = normalized[name];
            const sim = document.getElementById(`${name}_sim`);
            const nao = document.getElementById(`${name}_nao`);
            const isTrue = val === 1 || val === '1' || val === true || val === 'true';
            const isFalse = val === 0 || val === '0' || val === false || val === 'false';
            if (sim && nao) {
                sim.checked = !!isTrue;
                nao.checked = !!isFalse;
            }
            });
        }

        // Fallback: preenche a partir do dataset da option (quando fetch falhar)
        function preencherFromOptionDataset(opt) {
            if (!opt) return;
            const ds = opt.dataset || {};
            const id = {
            nome: opt.textContent ? opt.textContent.trim() : (ds.nome || ''),
            data_nascimento: ds.datanascimento || ds.dataNasc || ds.data_nascimento || '',
            sexo: ds.sexo || '',
            idade: ds.idade || ds.age || ''
            };
            preencherIdentificacao(id);
        }

        // Função principal: busca via fetch e preenche campos
        async function carregarAnaliseDoAluno(alunoId) {
            if (!alunoId) return;

            const opt = select.options[select.selectedIndex];
            if (opt && (opt.dataset && (opt.dataset.datanascimento || opt.dataset.sexo || opt.dataset.idade))) {
            // se option já tem identificação, preenche rápido
            preencherFromOptionDataset(opt);
            } else {
            // limpa identificação temporariamente
            if (dataNascEl) dataNascEl.value = '';
            if (sexoMEl) sexoMEl.checked = false;
            if (sexoFEl) sexoFEl.checked = false;
            if (idadeDisplayEl) idadeDisplayEl.value = '';
            if (idadeHiddenEl) idadeHiddenEl.value = '';
            }

            const urlTemplate = window.routes && window.routes.lastAnalysis ? window.routes.lastAnalysis : null;
            if (!urlTemplate) {
            console.error('Rota lastAnalysis não definida em window.routes');
            return;
            }
            const url = urlTemplate.replace('__ID__', encodeURIComponent(alunoId));

            try {
            const resp = await fetch(url, { credentials: 'same-origin' });

            // tenta sempre ler JSON (mesmo em 404) para aproveitar identificação quando presente
            let payload;
            try {
                payload = await resp.json();
            } catch (e) {
                console.error('Resposta não é JSON', e);
                return;
            }

            // DEBUG mínimo (remova depois)
            console.debug('lastAnalysis status', resp.status, 'payload keys', Object.keys(payload || {}));

            // identificação: prioriza payload.identificacao, senão monta a partir do topo
            const idObj = payload.identificacao ?? {
                nome: payload.nome ?? null,
                data_nascimento: payload.data_nascimento ?? null,
                sexo: payload.sexo ?? null,
                idade: payload.idade ?? null
            };
            preencherIdentificacao(idObj);

            // preencher análise: normalizeAnalisePayload aceita topo ou grupos
            preencherAnalise(payload);

            // se a resposta for 404 e não houver campos de análise, apenas logamos (não interrompe)
            if (!resp.ok && resp.status === 404) {
                console.warn('fetch retornou 404 — identificação preenchida, análise ausente');
            }
            } catch (err) {
            console.error('Erro ao carregar última análise:', err);
            if (opt) preencherFromOptionDataset(opt);
            }
        }

        // Listener de mudança no select (mesmo comportamento que você tinha)
        select.addEventListener('change', () => {
            const id = select.value;
            if (!id) return;
            carregarAnaliseDoAluno(id);
        });

        // Se já houver uma opção selecionada no carregamento (old value), disparamos a mudança para preencher campos
        const initialOpt = select.options[select.selectedIndex];
        if (initialOpt && initialOpt.value !== '') {
            setTimeout(() => select.dispatchEvent(new Event('change', { bubbles: true })), 50);
        }

        // Atualiza idade automaticamente se o usuário editar a data de nascimento manualmente
        if (dataNascEl) {
            dataNascEl.addEventListener('change', () => {
            const val = dataNascEl.value;
            if (!val) {
                if (idadeDisplayEl) idadeDisplayEl.value = '';
                if (idadeHiddenEl) idadeHiddenEl.value = '';
                return;
            }
            const hoje = new Date();
            const nasc = new Date(val + 'T00:00:00');
            let idadeCalc = hoje.getFullYear() - nasc.getFullYear();
            const m = hoje.getMonth() - nasc.getMonth();
            if (m < 0 || (m === 0 && hoje.getDate() < nasc.getDate())) idadeCalc--;
            if (idadeCalc >= 0) {
                if (idadeDisplayEl) idadeDisplayEl.value = idadeCalc + ' anos';
                if (idadeHiddenEl) idadeHiddenEl.value = idadeCalc;
            } else {
                if (idadeDisplayEl) idadeDisplayEl.value = '';
                if (idadeHiddenEl) idadeHiddenEl.value = '';
            }
            });
        }

        // Auto-hide success alert (melhor comportamento: usa classes do Bootstrap se disponível)
        const alert = document.querySelector('.alert-success');
        if (alert) {
            const TIMEOUT = 5000;
            if (!alert.classList.contains('fade')) alert.classList.add('fade', 'show');
            setTimeout(() => {
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
                return;
                }
            } catch (e) {}
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => { if (alert.parentNode) alert.parentNode.removeChild(alert); }, 500);
            }, TIMEOUT);
        }
        });
    </script>
@endpush


