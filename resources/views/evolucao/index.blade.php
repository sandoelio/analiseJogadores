@extends('layouts.app')

@section('title', 'Evolucao do Atleta')

@push('styles')
    <style>
        .evolucao-shell {
            max-width: 1080px;
            margin: 0 auto;
            padding: 0.9rem 0 1.25rem;
        }

        .evolucao-topo,
        .evolucao-filtros,
        .evolucao-resumo,
        .evolucao-card {
            border: 1px solid #dbe1ec;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08);
        }

        .evolucao-topo {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.95rem 1rem;
            margin-bottom: 0.8rem;
        }

        .evolucao-chip {
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

        .evolucao-title {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.42rem;
            font-weight: 700;
        }

        .evolucao-texto {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .evolucao-voltar {
            min-width: 130px;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            font-weight: 700;
            background: #28365F;
            border-color: #28365F;
        }

        .evolucao-voltar:hover {
            background: #1f2d4f;
            border-color: #1f2d4f;
        }

        .evolucao-filtros {
            padding: 0.9rem 1rem 1rem;
            margin-bottom: 0.9rem;
        }

        .evolucao-filtros-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
            gap: 0.8rem;
            align-items: end;
        }

        .evolucao-campo {
            display: grid;
            gap: 0.4rem;
        }

        .evolucao-label {
            color: #33405f;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .evolucao-select {
            min-height: 46px;
            border-radius: 0.85rem;
            border-color: #dbe1ec;
        }

        .evolucao-btn {
            min-height: 44px;
            padding: 0.6rem 1rem;
            border-radius: 0.85rem;
            font-weight: 700;
        }

        .evolucao-btn-principal {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .evolucao-btn-principal:hover {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .evolucao-vazio {
            margin: 0.8rem 0 0;
            padding: 0.9rem 1rem;
            border-radius: 0.9rem;
            background: #f7f9fc;
            color: #5f6b85;
            font-weight: 600;
        }

        .evolucao-resultado {
            display: grid;
            gap: 0.9rem;
        }

        .evolucao-resumo {
            padding: 1rem;
        }

        .evolucao-nome {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .evolucao-meta {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.88rem;
            line-height: 1.45;
        }

        .evolucao-grid {
            display: grid;
            gap: 0.9rem;
        }

        .evolucao-grid-atleta {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .evolucao-grid-tecnico {
            grid-template-columns: repeat(5, minmax(0, 1fr));
            margin-bottom: 0.95rem;
        }

        .evolucao-card {
            padding: 0.9rem;
        }

        .evolucao-card-topo {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.55rem;
        }

        .evolucao-card-icone {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            background: #eef3fb;
            color: #28365F;
            font-size: 1.1rem;
        }

        .evolucao-card-valor {
            color: #1f2d4f;
            font-size: 1.45rem;
            font-weight: 700;
            line-height: 1;
            text-align: right;
        }

        .evolucao-card-titulo {
            margin: 0;
            color: #1f2d4f;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .evolucao-card-texto {
            margin: 0.2rem 0 0;
            color: #5f6b85;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        .evolucao-status-subiu .evolucao-card-icone {
            background: #eaf7ee;
            color: #237a43;
        }

        .evolucao-status-caiu .evolucao-card-icone {
            background: #fff0ef;
            color: #c74e4e;
        }

        .evolucao-status-manteve .evolucao-card-icone,
        .evolucao-status-sem_base .evolucao-card-icone {
            background: #eef3fb;
            color: #28365F;
        }

        .evolucao-secao-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .evolucao-tabs-card {
            padding: 0.9rem;
        }

        .evolucao-tabs-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
            margin-bottom: 0.85rem;
        }

        .evolucao-tab-btn {
            padding: 0.58rem 0.95rem;
            border: 1px solid #dbe1ec;
            border-radius: 0.85rem;
            background: #f7f9fc;
            color: #33405f;
            font-size: 0.84rem;
            font-weight: 700;
            line-height: 1.2;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }

        .evolucao-tab-btn.active {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .evolucao-tab-panel {
            display: none;
        }

        .evolucao-tab-panel.active {
            display: block;
        }

        .evolucao-lista {
            display: grid;
            gap: 0.55rem;
        }

        .evolucao-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            padding-bottom: 0.45rem;
            border-bottom: 1px solid #edf2f8;
        }

        .evolucao-item:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }

        .evolucao-item-label {
            color: #1f2d4f;
            font-weight: 700;
        }

        .evolucao-item-meta {
            color: #5f6b85;
            font-size: 0.8rem;
            margin-top: 0.15rem;
        }

        .evolucao-item-valor {
            color: #1f2d4f;
            font-weight: 700;
            text-align: right;
        }

        .evolucao-tabela-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .evolucao-tabela {
            width: 100%;
            min-width: 760px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .evolucao-tabela th,
        .evolucao-tabela td {
            padding: 0.8rem 0.9rem;
            border: 1px solid #d8dee9;
            text-align: left;
        }

        .evolucao-tabela th {
            background: #223154;
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .evolucao-tabela td {
            background: #fff;
            color: #263248;
            font-size: 0.88rem;
        }

        .evolucao-tabela td:first-child {
            font-weight: 700;
            background: #f8fafd;
        }

        .evolucao-mobile-lista {
            display: none;
        }

        .evolucao-mobile-card {
            border: 1px solid #e5eaf3;
            border-radius: 0.95rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 0.85rem 0.9rem;
        }

        .evolucao-mobile-card + .evolucao-mobile-card {
            margin-top: 0.7rem;
        }

        .evolucao-mobile-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .evolucao-mobile-titulo {
            margin: 0;
            color: #1f2d4f;
            font-size: 0.94rem;
            font-weight: 700;
        }

        .evolucao-mobile-meta {
            color: #5f6b85;
            font-size: 0.8rem;
        }

        @media (max-width: 991.98px) {
            .evolucao-filtros-grid {
                grid-template-columns: 1fr 1fr;
            }

            .evolucao-grid-tecnico {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .evolucao-topo {
                flex-direction: column;
            }

            .evolucao-filtros-grid {
                grid-template-columns: 1fr;
            }

            .evolucao-grid-atleta,
            .evolucao-secao-grid {
                grid-template-columns: 1fr;
            }

            .evolucao-tabs-nav {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .evolucao-shell {
                padding-top: 0.45rem;
            }

            .evolucao-title {
                font-size: 1.16rem;
            }

            .evolucao-texto {
                font-size: 0.84rem;
            }

            .evolucao-voltar {
                width: 100%;
            }

            .evolucao-resumo {
                padding: 0.85rem;
            }

            .evolucao-nome {
                font-size: 1.05rem;
            }

            .evolucao-meta {
                font-size: 0.8rem;
                line-height: 1.35;
            }

            .evolucao-grid-tecnico {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.55rem;
            }

            .evolucao-card-mobile-span-2 {
                grid-column: 1 / -1;
            }

            .evolucao-grid-tecnico {
                margin-bottom: 0.75rem;
            }

            .evolucao-card {
                padding: 0.65rem;
            }

            .evolucao-card-topo {
                margin-bottom: 0.28rem;
            }

            .evolucao-card-icone {
                width: 32px;
                height: 32px;
                font-size: 0.84rem;
                border-radius: 0.7rem;
            }

            .evolucao-card-valor {
                font-size: 0.96rem;
            }

            .evolucao-card-titulo {
                font-size: 0.78rem;
                line-height: 1.2;
            }

            .evolucao-card-texto {
                margin-top: 0.15rem;
                font-size: 0.7rem;
                line-height: 1.25;
            }

            .evolucao-tabs-card {
                padding: 0.8rem;
            }

            .evolucao-tabs-nav {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0.4rem;
                margin-bottom: 0.7rem;
            }

            .evolucao-tab-btn {
                min-height: 44px;
                padding: 0.45rem 0.35rem;
                font-size: 0.73rem;
                border-radius: 0.75rem;
            }

            .evolucao-tabela-wrap {
                display: none;
            }

            .evolucao-mobile-lista {
                display: block;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $user = auth()->user();
        $isAdmin = auth()->check() && (int) ($user->is_admin ?? 0) === 1;
        $isPrivilegiado = auth()->check() && ! auth('athlete')->check();
        $instituicaoId = $isAdmin ? null : (auth('athlete')->id() ?? (auth()->check() ? ($user->instituicao_id ?? null) : null));
        $rotaVoltar = auth('athlete')->check()
            ? route('public.dashboard')
            : ($isAdmin ? route('admin.dashboard') : route('tecnico.dashboard'));
    @endphp

    <div class="evolucao-shell">
        <div class="evolucao-topo">
            <div>
                <span class="evolucao-chip">
                    <i class="bi bi-activity"></i>
                    Evolucao
                </span>
                <p class="evolucao-texto">
                    @if ($isPrivilegiado)
                        Compare a ultima analise com a anterior para entender os avancos, as manutencoes e os pontos que exigem desenvolvimento.
                    @else
                        Veja de forma simples se voce subiu, caiu ou manteve seus fundamentos na ultima comparacao disponivel.
                    @endif
                </p>
            </div>

            <a href="{{ $rotaVoltar }}" class="btn btn-primary evolucao-voltar">
                <i class="bi bi-house-door me-1"></i>
                Voltar
            </a>
        </div>

        <div class="evolucao-filtros">
            <div class="evolucao-filtros-grid">
                @if ($isAdmin)
                    <div class="evolucao-campo">
                        <label class="evolucao-label" for="instituicao">Instituicao</label>
                        <select id="instituicao" class="form-select evolucao-select">
                            <option value="">Selecione a instituicao</option>
                            @foreach ($instituicoes as $instituicao)
                                <option value="{{ $instituicao->id }}">{{ $instituicao->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="evolucao-campo">
                    <label class="evolucao-label" for="aluno">Atleta</label>
                    <select id="aluno" class="form-select evolucao-select" @disabled($isAdmin)>
                        <option value="">{{ $isAdmin ? 'Selecione a instituicao primeiro' : 'Carregando atletas...' }}</option>
                    </select>
                </div>

                <button id="btn-evolucao" type="button" class="btn evolucao-btn evolucao-btn-principal" disabled>
                    Ver evolucao
                </button>
            </div>

            <p id="evolucao-vazio" class="evolucao-vazio d-none"></p>
        </div>

        <div id="evolucao-resultado" class="evolucao-resultado d-none">
            <div class="evolucao-resumo">
                <h2 id="resumo-nome" class="evolucao-nome"></h2>
                <p id="resumo-meta" class="evolucao-meta"></p>
            </div>

            <div id="painel-atleta" class="d-none">
                <div class="evolucao-card evolucao-tabs-card">
                    <div class="evolucao-tabs-nav" role="tablist" aria-label="Abas da evolucao do atleta">
                        <button type="button" class="evolucao-tab-btn active" data-tab-target="atleta-tab-fundamentos">
                            Fundamentos
                        </button>
                        <button type="button" class="evolucao-tab-btn" data-tab-target="atleta-tab-fortes">
                            Pontos fortes
                        </button>
                        <button type="button" class="evolucao-tab-btn" data-tab-target="atleta-tab-desenvolver">
                            A desenvolver
                        </button>
                    </div>

                    <div id="atleta-tab-fundamentos" class="evolucao-tab-panel active">
                        <div id="atleta-campos" class="evolucao-grid evolucao-grid-atleta"></div>
                    </div>

                    <div id="atleta-tab-fortes" class="evolucao-tab-panel">
                        <div id="atleta-fortes" class="evolucao-lista mt-3"></div>
                    </div>

                    <div id="atleta-tab-desenvolver" class="evolucao-tab-panel">
                        <div id="atleta-desenvolver" class="evolucao-lista mt-3"></div>
                    </div>
                </div>
            </div>

            <div id="painel-tecnico" class="d-none">
                <div id="tecnico-resumo-cards" class="evolucao-grid evolucao-grid-tecnico"></div>

                <div class="evolucao-card evolucao-tabs-card">
                    <div class="evolucao-tabs-nav" role="tablist" aria-label="Abas da evolucao tecnica">
                        <button type="button" class="evolucao-tab-btn active" data-tab-target="tecnico-tab-analitica">
                            Leitura analitica
                        </button>
                        <button type="button" class="evolucao-tab-btn" data-tab-target="tecnico-tab-fortes">
                            Pontos fortes
                        </button>
                        <button type="button" class="evolucao-tab-btn" data-tab-target="tecnico-tab-desenvolver">
                            Pontos a desenvolver
                        </button>
                    </div>

                    <div id="tecnico-tab-analitica" class="evolucao-tab-panel active">
                        <div class="evolucao-tabela-wrap mt-3">
                            <table class="evolucao-tabela">
                                <thead>
                                    <tr>
                                        <th>Fundamento</th>
                                        <th>Anterior</th>
                                        <th>Atual</th>
                                        <th>Delta</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="tecnico-tabela"></tbody>
                            </table>
                        </div>

                        <div id="tecnico-mobile-lista" class="evolucao-mobile-lista mt-3"></div>
                    </div>

                    <div id="tecnico-tab-fortes" class="evolucao-tab-panel">
                        <h3 class="evolucao-card-titulo">Pontos fortes atuais</h3>
                        <div id="tecnico-fortes" class="evolucao-lista mt-3"></div>
                    </div>

                    <div id="tecnico-tab-desenvolver" class="evolucao-tab-panel">
                        <h3 class="evolucao-card-titulo">Pontos a desenvolver</h3>
                        <div id="tecnico-desenvolver" class="evolucao-lista mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isAdmin = @json($isAdmin);
            const isPrivilegiado = @json($isPrivilegiado);
            const instituicaoEfetiva = @json($instituicaoId);
            const alunosBase = "{{ url('/analise/instituicao') }}";
            const evolucaoBase = "{{ url('/evolucao/aluno') }}";

            const instituicaoSelect = document.getElementById('instituicao');
            const alunoSelect = document.getElementById('aluno');
            const btn = document.getElementById('btn-evolucao');
            const vazio = document.getElementById('evolucao-vazio');
            const resultado = document.getElementById('evolucao-resultado');
            const painelAtleta = document.getElementById('painel-atleta');
            const painelTecnico = document.getElementById('painel-tecnico');
            const tabButtons = document.querySelectorAll('.evolucao-tab-btn');

            function escapeHtml(value) {
                if (value === null || value === undefined) return '--';
                return String(value).replace(/[&<>"'`=\/]/g, function(c) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;',
                        '/': '&#x2F;',
                        '`': '&#x60;',
                        '=': '&#x3D;'
                    }[c];
                });
            }

            function formatDateBR(value) {
                if (!value) return '--';
                const data = new Date(`${value}T00:00:00`);
                return Number.isNaN(data.getTime()) ? '--' : data.toLocaleDateString('pt-BR');
            }

            function renderListaSimples(containerId, itens) {
                const container = document.getElementById(containerId);
                container.innerHTML = '';

                itens.forEach(item => {
                    const bloco = document.createElement('div');
                    bloco.className = 'evolucao-item';
                    bloco.innerHTML = `
                        <div>
                            <div class="evolucao-item-label">${escapeHtml(item.label)}</div>
                            <div class="evolucao-item-meta">${escapeHtml(item.status)}</div>
                        </div>
                        <div class="evolucao-item-valor">${escapeHtml(item.valor)}</div>
                    `;
                    container.appendChild(bloco);
                });
            }

            function renderAtleta(data) {
                document.getElementById('atleta-campos').innerHTML = data.evolucao.map(item => `
                    <div class="evolucao-card evolucao-status-${item.status}">
                        <div class="evolucao-card-topo">
                            <span class="evolucao-card-icone">
                                <i class="bi ${item.icone}"></i>
                            </span>
                            <div class="evolucao-card-valor">${escapeHtml(item.atual)}</div>
                        </div>
                        <h3 class="evolucao-card-titulo">${escapeHtml(item.label)}</h3>
                        <p class="evolucao-card-texto">${escapeHtml(item.texto_curto)}</p>
                    </div>
                `).join('');

                renderListaSimples('atleta-fortes', data.pontos_fortes);
                renderListaSimples('atleta-desenvolver', data.pontos_desenvolver);
            }

            function renderTecnico(data) {
                const resumoCards = [{
                        titulo: 'Subiu',
                        valor: data.resumo.subiu,
                        icone: 'bi-arrow-up-right',
                        status: 'subiu'
                    },
                    {
                        titulo: 'Manteve',
                        valor: data.resumo.manteve,
                        icone: 'bi-dash-lg',
                        status: 'manteve'
                    },
                    {
                        titulo: 'Caiu',
                        valor: data.resumo.caiu,
                        icone: 'bi-arrow-down-right',
                        status: 'caiu'
                    },
                    {
                        titulo: 'Maior alta',
                        valor: data.resumo.maior_alta,
                        icone: 'bi-graph-up-arrow',
                        status: 'subiu'
                    },
                    {
                        titulo: 'Maior queda',
                        valor: data.resumo.maior_queda,
                        icone: 'bi-graph-down-arrow',
                        status: 'caiu',
                        fullMobile: true
                    }
                ];

                document.getElementById('tecnico-resumo-cards').innerHTML = resumoCards.map(item => `
                    <div class="evolucao-card evolucao-status-${item.status} ${item.fullMobile ? 'evolucao-card-mobile-span-2' : ''}">
                        <div class="evolucao-card-topo">
                            <span class="evolucao-card-icone">
                                <i class="bi ${item.icone}"></i>
                            </span>
                            <div class="evolucao-card-valor">${escapeHtml(item.valor)}</div>
                        </div>
                        <h3 class="evolucao-card-titulo">${escapeHtml(item.titulo)}</h3>
                    </div>
                `).join('');

                const tabela = document.getElementById('tecnico-tabela');
                tabela.innerHTML = data.evolucao.map(item => `
                    <tr>
                        <td>${escapeHtml(item.label)}</td>
                        <td>${escapeHtml(item.anterior)}</td>
                        <td>${escapeHtml(item.atual)}</td>
                        <td>${item.delta === null ? '--' : escapeHtml(item.delta)}</td>
                        <td>${escapeHtml(item.texto_curto)}</td>
                    </tr>
                `).join('');

                document.getElementById('tecnico-mobile-lista').innerHTML = data.evolucao.map(item => `
                    <div class="evolucao-mobile-card">
                        <div class="evolucao-mobile-head">
                            <div>
                                <h4 class="evolucao-mobile-titulo">${escapeHtml(item.label)}</h4>
                                <div class="evolucao-mobile-meta">${escapeHtml(item.texto_curto)}</div>
                            </div>
                            <span class="evolucao-card-icone">
                                <i class="bi ${item.icone}"></i>
                            </span>
                        </div>
                        <div class="evolucao-item">
                            <span class="evolucao-item-label">Anterior</span>
                            <span class="evolucao-item-valor">${escapeHtml(item.anterior)}</span>
                        </div>
                        <div class="evolucao-item">
                            <span class="evolucao-item-label">Atual</span>
                            <span class="evolucao-item-valor">${escapeHtml(item.atual)}</span>
                        </div>
                    </div>
                `).join('');

                renderListaSimples('tecnico-fortes', data.pontos_fortes);
                renderListaSimples('tecnico-desenvolver', data.pontos_desenvolver);
            }

            function renderResultado(data) {
                document.getElementById('resumo-nome').textContent = data.identificacao.nome;
                const metaDesktop = `${data.identificacao.instituicao || '--'} | ${data.identificacao.idade ?? '--'} anos | ${data.identificacao.sexo || '--'} | Ultima analise: ${formatDateBR(data.identificacao.ultima_analise)} | Analise anterior: ${formatDateBR(data.identificacao.analise_anterior)}`;
                const metaMobile = `${data.identificacao.instituicao || '--'} | ${data.identificacao.idade ?? '--'} anos | ${data.identificacao.sexo || '--'}`;
                document.getElementById('resumo-meta').textContent = window.innerWidth <= 575 ? metaMobile : metaDesktop;

                if (isPrivilegiado) {
                    painelAtleta.classList.add('d-none');
                    painelTecnico.classList.remove('d-none');
                    renderTecnico(data);
                } else {
                    painelTecnico.classList.add('d-none');
                    painelAtleta.classList.remove('d-none');
                    renderAtleta(data);
                }

                resultado.classList.remove('d-none');
            }

            function ativarAba(targetId) {
                const grupo = targetId.startsWith('tecnico-') ? 'tecnico-' : 'atleta-';
                const panels = document.querySelectorAll(`.evolucao-tab-panel[id^="${grupo}"]`);
                const buttons = document.querySelectorAll(`.evolucao-tab-btn[data-tab-target^="${grupo}"]`);

                buttons.forEach(button => {
                    button.classList.toggle('active', button.dataset.tabTarget === targetId);
                });

                panels.forEach(panel => {
                    panel.classList.toggle('active', panel.id === targetId);
                });
            }

            function carregarAlunos(instituicaoId) {
                alunoSelect.disabled = true;
                alunoSelect.innerHTML = '<option value="">Carregando atletas...</option>';
                btn.disabled = true;
                resultado.classList.add('d-none');

                fetch(`${alunosBase}/${instituicaoId}/alunos`, {
                        credentials: 'same-origin'
                    })
                    .then(response => response.json())
                    .then(alunos => {
                        alunoSelect.innerHTML = '<option value="">Selecione o atleta</option>';

                        if (!alunos.length) {
                            alunoSelect.innerHTML = '<option value="">Nenhum atleta encontrado</option>';
                            vazio.textContent = 'Nao ha atletas disponiveis para este acesso.';
                            vazio.classList.remove('d-none');
                            return;
                        }

                        alunos.forEach(aluno => {
                            const option = document.createElement('option');
                            option.value = aluno.matricula;
                            option.textContent = aluno.idade !== null ? `${aluno.nome} - ${aluno.idade} anos` : aluno.nome;
                            alunoSelect.appendChild(option);
                        });

                        vazio.classList.add('d-none');
                        alunoSelect.disabled = false;
                    })
                    .catch(() => {
                        alunoSelect.innerHTML = '<option value="">Erro ao carregar atletas</option>';
                        vazio.textContent = 'Nao foi possivel carregar os atletas agora.';
                        vazio.classList.remove('d-none');
                    });
            }

            if (!isAdmin && instituicaoEfetiva) {
                carregarAlunos(instituicaoEfetiva);
            }

            if (instituicaoSelect) {
                instituicaoSelect.addEventListener('change', () => {
                    const instituicaoId = instituicaoSelect.value;
                    if (!instituicaoId) return;
                    carregarAlunos(instituicaoId);
                });
            }

            alunoSelect.addEventListener('change', () => {
                btn.disabled = !alunoSelect.value;
                resultado.classList.add('d-none');
            });

            btn.addEventListener('click', () => {
                if (!alunoSelect.value) return;

                fetch(`${evolucaoBase}/${encodeURIComponent(alunoSelect.value)}`, {
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Falha ao carregar evolucao');
                        }
                        return response.json();
                    })
                    .then(renderResultado)
                    .catch(() => {
                        vazio.textContent = 'Nao foi possivel carregar a evolucao deste atleta.';
                        vazio.classList.remove('d-none');
                        resultado.classList.add('d-none');
                    });
            });

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    ativarAba(button.dataset.tabTarget);
                });
            });
        });
    </script>
@endpush
