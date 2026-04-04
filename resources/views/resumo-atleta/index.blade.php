@extends('layouts.app')

@section('title', 'Resumo do Atleta')

@push('styles')
    <style>
        .resumo-shell { max-width: 1020px; margin: 0 auto; padding: 0.95rem 0 1.2rem; }
        .resumo-topo, .resumo-filtros, .resumo-box { border: 1px solid #dbe1ec; border-radius: 1rem; background: #fff; box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08); }
        .resumo-topo { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 0.95rem 1rem; margin-bottom: 0.85rem; }
        .resumo-chip { display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 0.5rem; padding: 0.3rem 0.65rem; border-radius: 999px; background: #eef3fb; color: #28365F; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; }
        .resumo-title { margin: 0; color: #1f2d4f; font-size: 1.38rem; font-weight: 700; }
        .resumo-texto { margin: 0.35rem 0 0; color: #5f6b85; font-size: 0.9rem; line-height: 1.45; }
        .resumo-topo-acoes { display: flex; align-items: center; gap: 0.65rem; }
        .resumo-topo-btn { min-height: 42px; display: inline-flex; align-items: center; justify-content: center; padding: 0.6rem 1rem; border-radius: 0.85rem; font-weight: 700; text-decoration: none; white-space: nowrap; }
        .resumo-topo-btn-principal { background: #28365F; border: 1px solid #28365F; color: #fff; }
        .resumo-topo-btn-principal:hover { background: #1f2d4f; border-color: #1f2d4f; color: #fff; }
        .resumo-topo-btn-secundario { background: #f5f7fb; border: 1px solid #dbe1ec; color: #33405f; }
        .resumo-filtros { padding: 0.9rem 1rem 1rem; margin-bottom: 0.9rem; }
        .resumo-filtros-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto; gap: 0.8rem; align-items: end; }
        .resumo-campo { display: grid; gap: 0.4rem; }
        .resumo-label { color: #33405f; font-size: 0.84rem; font-weight: 700; }
        .resumo-select { min-height: 46px; border-radius: 0.85rem; border-color: #dbe1ec; }
        .resumo-btn { min-height: 44px; padding: 0.6rem 1rem; border-radius: 0.85rem; font-weight: 700; background: #28365F; border-color: #28365F; color: #fff; }
        .resumo-btn:hover { background: #1f2d4f; border-color: #1f2d4f; color: #fff; }
        .resumo-vazio { margin: 0.75rem 0 0; padding: 0.8rem 0.9rem; border-radius: 0.9rem; background: #f7f9fc; color: #5f6b85; font-weight: 600; }
        .resumo-resultado { display: grid; gap: 0.9rem; }
        .resumo-box { padding: 0.95rem 1rem; }
        .resumo-nome { margin: 0; color: #1f2d4f; font-size: 1.18rem; font-weight: 700; }
        .resumo-meta { margin: 0.35rem 0 0; color: #5f6b85; font-size: 0.88rem; line-height: 1.45; }
        .resumo-tabs { display: flex; flex-wrap: wrap; gap: 0.55rem; margin-bottom: 0.85rem; }
        .resumo-tab-btn { padding: 0.58rem 0.95rem; border: 1px solid #dbe1ec; border-radius: 0.85rem; background: #f7f9fc; color: #33405f; font-size: 0.84rem; font-weight: 700; }
        .resumo-tab-btn.active { background: #28365F; border-color: #28365F; color: #fff; }
        .resumo-panel { display: none; }
        .resumo-panel.active { display: block; }
        .resumo-grid { display: grid; gap: 0.9rem; }
        .resumo-grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .resumo-grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .resumo-card { border: 1px solid #e5eaf3; border-radius: 0.95rem; background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); padding: 0.9rem; }
        .resumo-card-titulo { margin: 0; color: #1f2d4f; font-size: 0.94rem; font-weight: 700; }
        .resumo-card-texto { margin: 0.4rem 0 0; color: #5f6b85; font-size: 0.84rem; line-height: 1.45; }
        .resumo-numero { color: #1f2d4f; font-size: 1.42rem; font-weight: 700; line-height: 1; }
        .resumo-badge { display: inline-flex; align-items: center; justify-content: center; min-height: 34px; padding: 0.35rem 0.7rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700; }
        .resumo-badge-acima { background: #eaf7ee; color: #237a43; }
        .resumo-badge-dentro { background: #eef3fb; color: #28365F; }
        .resumo-badge-abaixo { background: #fff0ef; color: #c74e4e; }
        .resumo-badge-sem_base { background: #edf2f8; color: #5f6b85; }
        .resumo-lista { display: grid; gap: 0.55rem; }
        .resumo-item { padding-bottom: 0.45rem; border-bottom: 1px solid #edf2f8; color: #1f2d4f; font-weight: 600; }
        .resumo-item:last-child { padding-bottom: 0; border-bottom: none; }
        .resumo-status-card { background: linear-gradient(135deg, #28365F 0%, #354a7f 100%); color: #fff; border-color: rgba(255, 255, 255, 0.08); }
        .resumo-status-card .resumo-card-titulo,
        .resumo-status-card .resumo-card-texto,
        .resumo-status-card .resumo-numero { color: #fff; }
        .resumo-status-chip { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.3rem 0.65rem; border-radius: 999px; background: rgba(255, 255, 255, 0.14); color: #fff; font-size: 0.75rem; font-weight: 700; }
        .resumo-status-motivo { display: inline-flex; align-items: center; min-height: 30px; padding: 0.28rem 0.65rem; border-radius: 999px; background: rgba(255, 255, 255, 0.12); font-size: 0.76rem; font-weight: 700; }
        .resumo-selos-grid { display: grid; gap: 0.75rem; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .resumo-selo { display: flex; gap: 0.75rem; align-items: flex-start; }
        .resumo-selo-icone { width: 40px; height: 40px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 0.9rem; background: #eef3fb; color: #28365F; font-size: 1rem; }
        .resumo-meta-atual-grid { display: grid; gap: 0.75rem; grid-template-columns: minmax(0, 1.4fr) repeat(3, minmax(0, 0.75fr)); }
        .resumo-meta-mini { padding: 0.7rem 0.75rem; border-radius: 0.9rem; background: #f7f9fc; border: 1px solid #edf2f8; }
        .resumo-meta-mini-rotulo { color: #5f6b85; font-size: 0.74rem; font-weight: 700; text-transform: uppercase; }
        .resumo-meta-mini-valor { margin-top: 0.2rem; color: #1f2d4f; font-weight: 700; }
        .resumo-recomendacao { border-left: 4px solid #28365F; }
        .resumo-grupo-destaque { display: grid; gap: 0.8rem; grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr); }
        .resumo-grupo-texto { display: grid; gap: 0.45rem; }
        .resumo-grupo-indicadores { display: grid; gap: 0.7rem; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .resumo-indicador-box { padding: 0.75rem; border-radius: 0.9rem; background: #f7f9fc; border: 1px solid #edf2f8; }
        @media (max-width: 991.98px) { .resumo-filtros-grid { grid-template-columns: 1fr 1fr; } .resumo-grid-3 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 991.98px) { .resumo-selos-grid, .resumo-meta-atual-grid, .resumo-grupo-destaque { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 767.98px) { .resumo-topo { flex-direction: column; } .resumo-topo-acoes { width: 100%; flex-direction: column; } .resumo-topo-btn { width: 100%; } .resumo-filtros-grid, .resumo-grid-2, .resumo-grid-3, .resumo-selos-grid, .resumo-meta-atual-grid, .resumo-grupo-destaque, .resumo-grupo-indicadores { grid-template-columns: 1fr; } .resumo-tabs { display: grid; grid-template-columns: 1fr; } }
        @media (max-width: 575.98px) { .resumo-shell { padding-top: 0.45rem; } .resumo-topo, .resumo-filtros, .resumo-box { border-radius: 0.9rem; } .resumo-title { font-size: 1.14rem; } .resumo-texto, .resumo-meta, .resumo-card-texto { font-size: 0.8rem; line-height: 1.35; } .resumo-box, .resumo-card, .resumo-filtros, .resumo-topo { padding: 0.8rem; } .resumo-numero { font-size: 1.18rem; } .resumo-selo { gap: 0.6rem; } .resumo-selo-icone { width: 34px; height: 34px; border-radius: 0.8rem; font-size: 0.9rem; } .resumo-status-chip, .resumo-status-motivo, .resumo-badge { font-size: 0.72rem; } }
    </style>
@endpush

@section('content')
    @php
        $user = auth()->user();
        $isAdmin = auth()->check() && (int) ($user->is_admin ?? 0) === 1;
        $isPrivilegiado = auth()->check() && ! auth('athlete')->check();
        $instituicaoId = $isAdmin ? null : (auth('athlete')->id() ?? (auth()->check() ? ($user->instituicao_id ?? null) : null));
        $rotaVoltar = auth('athlete')->check() ? route('public.dashboard') : ($isAdmin ? route('admin.dashboard') : route('tecnico.dashboard'));
    @endphp

    <div class="resumo-shell">
        <div class="resumo-topo">
            <div>
                <span class="resumo-chip"><i class="bi bi-journal-text"></i> Resumo</span>
                <h1 class="resumo-title">Resumo inteligente do atleta</h1>
                <p class="resumo-texto">
                    @if ($isPrivilegiado)
                        Consulte a narrativa automatica do progresso recente e a posicao tecnica do atleta no grupo de mesma idade e sexo.
                    @else
                        Veja um resumo curto do seu progresso recente e onde voce esta em relacao ao grupo da sua idade e sexo.
                    @endif
                </p>
            </div>

            <div class="resumo-topo-acoes">
                <a href="{{ route('evolucao.index') }}" class="resumo-topo-btn resumo-topo-btn-secundario"><i class="bi bi-activity me-1"></i> Evolucao</a>
                <a href="{{ $rotaVoltar }}" class="resumo-topo-btn resumo-topo-btn-principal"><i class="bi bi-house-door me-1"></i> Voltar</a>
            </div>
        </div>

        <div class="resumo-filtros">
            <div class="resumo-filtros-grid">
                @if ($isAdmin)
                    <div class="resumo-campo">
                        <label class="resumo-label" for="instituicao">Instituicao</label>
                        <select id="instituicao" class="form-select resumo-select">
                            <option value="">Selecione a instituicao</option>
                            @foreach ($instituicoes as $instituicao)
                                <option value="{{ $instituicao->id }}">{{ $instituicao->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="resumo-campo">
                    <label class="resumo-label" for="aluno">Atleta</label>
                    <select id="aluno" class="form-select resumo-select" @disabled($isAdmin)>
                        <option value="">{{ $isAdmin ? 'Selecione a instituicao primeiro' : 'Carregando atletas...' }}</option>
                    </select>
                </div>

                <button id="btn-resumo" type="button" class="btn resumo-btn" disabled>Ver resumo</button>
            </div>

            <p id="resumo-vazio" class="resumo-vazio d-none"></p>
        </div>

        <div id="resumo-resultado" class="resumo-resultado d-none">
            <div class="resumo-box">
                <h2 id="resumo-nome" class="resumo-nome"></h2>
                <p id="resumo-meta" class="resumo-meta"></p>
            </div>

            <div class="resumo-box">
                <div class="resumo-tabs">
                    @if ($isPrivilegiado)
                        <button type="button" class="resumo-tab-btn active" data-tab-target="resumo-panel-progresso">Progresso</button>
                        <button type="button" class="resumo-tab-btn" data-tab-target="resumo-panel-grupo">Posicao no grupo</button>
                    @else
                        <button type="button" class="resumo-tab-btn active" data-tab-target="resumo-panel-momento">Meu momento</button>
                        <button type="button" class="resumo-tab-btn" data-tab-target="resumo-panel-progresso">Progresso</button>
                        <button type="button" class="resumo-tab-btn" data-tab-target="resumo-panel-grupo">Como estou no grupo</button>
                    @endif
                </div>

                @unless ($isPrivilegiado)
                    <div id="resumo-panel-momento" class="resumo-panel active">
                        <div class="resumo-card resumo-status-card">
                            <div class="d-flex flex-wrap justify-content-between gap-2">
                                <span class="resumo-status-chip"><i class="bi bi-lightning-charge-fill"></i> Meu momento</span>
                                <span id="status-motivo" class="resumo-status-motivo">--</span>
                            </div>
                            <h3 id="status-rotulo" class="resumo-card-titulo mt-3">--</h3>
                            <p id="status-mensagem" class="resumo-card-texto mt-2"></p>
                        </div>

                        <div class="resumo-card mt-3">
                            <h3 class="resumo-card-titulo">Selos do seu momento</h3>
                            <div id="selos-grid" class="resumo-selos-grid mt-3"></div>
                        </div>

                        <div class="resumo-card resumo-recomendacao mt-3">
                            <h3 class="resumo-card-titulo">Recomendacao rapida</h3>
                            <p id="recomendacao-curta" class="resumo-card-texto mt-2"></p>
                        </div>

                        <div class="resumo-card mt-3">
                            <h3 class="resumo-card-titulo">Meta atual visivel</h3>
                            <div class="resumo-meta-atual-grid mt-3">
                                <div class="resumo-meta-mini">
                                    <div class="resumo-meta-mini-rotulo">Meta</div>
                                    <div id="meta-titulo" class="resumo-meta-mini-valor">--</div>
                                    <p id="meta-texto" class="resumo-card-texto mt-2 mb-0"></p>
                                </div>
                                <div class="resumo-meta-mini">
                                    <div class="resumo-meta-mini-rotulo">Status</div>
                                    <div id="meta-status" class="resumo-meta-mini-valor">--</div>
                                </div>
                                <div class="resumo-meta-mini">
                                    <div class="resumo-meta-mini-rotulo">Prazo</div>
                                    <div id="meta-prazo" class="resumo-meta-mini-valor">--</div>
                                </div>
                                <div class="resumo-meta-mini">
                                    <div class="resumo-meta-mini-rotulo">Prioridade</div>
                                    <div id="meta-prioridade" class="resumo-meta-mini-valor">--</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endunless

                <div id="resumo-panel-progresso" class="resumo-panel active">
                    <div class="resumo-grid resumo-grid-3" id="narrativa-resumo-cards"></div>

                    <div class="resumo-card mt-3">
                        <h3 class="resumo-card-titulo">Narrativa automatica</h3>
                        <p id="narrativa-texto" class="resumo-card-texto"></p>
                    </div>

                    <div class="resumo-grid resumo-grid-2 mt-3">
                        <div class="resumo-card">
                            <h3 class="resumo-card-titulo">Evoluiu mais em</h3>
                            <div id="narrativa-melhoras" class="resumo-lista mt-3"></div>
                        </div>

                        <div class="resumo-card">
                            <h3 class="resumo-card-titulo">Pede mais atencao em</h3>
                            <div id="narrativa-quedas" class="resumo-lista mt-3"></div>
                        </div>
                    </div>
                </div>

                <div id="resumo-panel-grupo" class="resumo-panel">
                    @if ($isPrivilegiado)
                        <div class="resumo-grid resumo-grid-2">
                            <div class="resumo-card">
                                <h3 class="resumo-card-titulo">Posicao tecnica no grupo</h3>
                                <div class="mt-3"><span id="percentil-badge" class="resumo-badge resumo-badge-sem_base">Sem base</span></div>
                                <p id="percentil-descricao" class="resumo-card-texto mt-3"></p>
                            </div>

                            <div class="resumo-card">
                                <h3 class="resumo-card-titulo">Leitura de referencia</h3>
                                <div class="resumo-grid resumo-grid-2 mt-3">
                                    <div><div class="resumo-numero" id="percentil-valor">--</div><p class="resumo-card-texto">Percentil</p></div>
                                    <div><div class="resumo-numero" id="percentil-grupo">--</div><p class="resumo-card-texto">Posicao na lista</p></div>
                                    <div><div class="resumo-numero" id="percentil-score">--</div><p class="resumo-card-texto">Score do atleta</p></div>
                                    <div><div class="resumo-numero" id="percentil-media">--</div><p class="resumo-card-texto">Media do grupo</p></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="resumo-grupo-destaque">
                            <div class="resumo-card">
                                <h3 id="grupo-titulo" class="resumo-card-titulo">Como voce esta no grupo</h3>
                                <div class="mt-3"><span id="percentil-badge" class="resumo-badge resumo-badge-sem_base">Sem base</span></div>
                                <div class="resumo-grupo-texto mt-3">
                                    <p id="grupo-texto" class="resumo-card-texto mb-0"></p>
                                    <p id="percentil-descricao" class="resumo-card-texto mb-0"></p>
                                </div>
                            </div>

                            <div class="resumo-grupo-indicadores">
                                <div class="resumo-indicador-box">
                                    <div class="resumo-numero" id="percentil-valor">--</div>
                                    <p class="resumo-card-texto mb-0">Percentil</p>
                                </div>
                                <div class="resumo-indicador-box">
                                    <div class="resumo-numero" id="percentil-grupo">--</div>
                                    <p class="resumo-card-texto mb-0">Posicao na lista</p>
                                </div>
                                <div class="resumo-indicador-box">
                                    <div class="resumo-numero" id="percentil-score">--</div>
                                    <p class="resumo-card-texto mb-0">Score do atleta</p>
                                </div>
                                <div class="resumo-indicador-box">
                                    <div class="resumo-numero" id="percentil-media">--</div>
                                    <p class="resumo-card-texto mb-0">Media do grupo</p>
                                </div>
                            </div>
                        </div>
                    @endif
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
            const resumoBase = "{{ url('/resumo-atleta/aluno') }}";

            const instituicaoSelect = document.getElementById('instituicao');
            const alunoSelect = document.getElementById('aluno');
            const btn = document.getElementById('btn-resumo');
            const vazio = document.getElementById('resumo-vazio');
            const resultado = document.getElementById('resumo-resultado');
            const tabButtons = document.querySelectorAll('.resumo-tab-btn');

            function escapeHtml(value) {
                if (value === null || value === undefined || value === '') return '--';
                return String(value).replace(/[&<>"'`=\/]/g, function(c) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '/': '&#x2F;', '`': '&#x60;', '=': '&#x3D;' }[c];
                });
            }

            function formatDateBR(value) {
                if (!value) return '--';
                const data = new Date(`${value}T00:00:00`);
                return Number.isNaN(data.getTime()) ? '--' : data.toLocaleDateString('pt-BR');
            }

            function renderLista(containerId, itens, vazioTexto) {
                const container = document.getElementById(containerId);

                if (!itens.length) {
                    container.innerHTML = `<div class="resumo-item">${escapeHtml(vazioTexto)}</div>`;
                    return;
                }

                container.innerHTML = itens.map(item => `<div class="resumo-item">${escapeHtml(item)}</div>`).join('');
            }

            function ativarAba(targetId) {
                tabButtons.forEach(button => {
                    button.classList.toggle('active', button.dataset.tabTarget === targetId);
                });

                document.querySelectorAll('.resumo-panel').forEach(panel => {
                    panel.classList.toggle('active', panel.id === targetId);
                });
            }

            function renderResultado(data) {
                document.getElementById('resumo-nome').textContent = data.identificacao.nome;
                document.getElementById('resumo-meta').textContent =
                    `${data.identificacao.instituicao || '--'} | ${data.identificacao.idade ?? '--'} anos | ${data.identificacao.sexo || '--'} | Ultima analise: ${formatDateBR(data.identificacao.ultima_analise)} | Analise anterior: ${formatDateBR(data.identificacao.analise_anterior)}`;

                if (!isPrivilegiado) {
                    document.getElementById('status-motivo').textContent = data.status_principal.motivo;
                    document.getElementById('status-rotulo').textContent = data.status_principal.rotulo;
                    document.getElementById('status-mensagem').textContent = data.status_principal.mensagem;
                    document.getElementById('recomendacao-curta').textContent = data.recomendacao_curta;
                    document.getElementById('meta-titulo').textContent = data.meta_atual.titulo;
                    document.getElementById('meta-texto').textContent = data.meta_atual.texto;
                    document.getElementById('meta-status').textContent = data.meta_atual.status;
                    document.getElementById('meta-prazo').textContent = data.meta_atual.prazo;
                    document.getElementById('meta-prioridade').textContent = data.meta_atual.prioridade;
                    document.getElementById('grupo-titulo').textContent = data.grupo_resumo.titulo;
                    document.getElementById('grupo-texto').textContent = data.grupo_resumo.texto;

                    const selosGrid = document.getElementById('selos-grid');
                    selosGrid.innerHTML = data.selos.map(selo => `
                        <div class="resumo-card resumo-selo">
                            <span class="resumo-selo-icone"><i class="bi bi-${escapeHtml(selo.icone)}"></i></span>
                            <div>
                                <h3 class="resumo-card-titulo">${escapeHtml(selo.titulo)}</h3>
                                <p class="resumo-card-texto mb-0 mt-1">${escapeHtml(selo.texto)}</p>
                            </div>
                        </div>
                    `).join('');
                }

                document.getElementById('narrativa-resumo-cards').innerHTML = `
                    <div class="resumo-card">
                        <h3 class="resumo-card-titulo">Subiu</h3>
                        <div class="resumo-numero mt-3">${escapeHtml(data.narrativa.resumo.subiu)}</div>
                    </div>
                    <div class="resumo-card">
                        <h3 class="resumo-card-titulo">Manteve</h3>
                        <div class="resumo-numero mt-3">${escapeHtml(data.narrativa.resumo.manteve)}</div>
                    </div>
                    <div class="resumo-card">
                        <h3 class="resumo-card-titulo">Caiu</h3>
                        <div class="resumo-numero mt-3">${escapeHtml(data.narrativa.resumo.caiu)}</div>
                    </div>
                `;

                document.getElementById('narrativa-texto').textContent = isPrivilegiado ? data.narrativa.texto_tecnico : data.narrativa.texto_atleta;
                renderLista('narrativa-melhoras', data.narrativa.melhoras, 'Sem ganhos relevantes na comparacao atual.');
                renderLista('narrativa-quedas', data.narrativa.quedas, 'Nenhuma queda relevante na comparacao atual.');

                const percentil = data.percentil;
                const badge = document.getElementById('percentil-badge');
                badge.className = `resumo-badge resumo-badge-${percentil.class_key}`;
                badge.textContent = percentil.classificacao;
                document.getElementById('percentil-descricao').textContent = percentil.descricao;
                document.getElementById('percentil-valor').textContent = percentil.percentil;
                document.getElementById('percentil-grupo').textContent = percentil.posicao_lista;
                document.getElementById('percentil-score').textContent = percentil.score_atleta;
                document.getElementById('percentil-media').textContent = percentil.media_grupo;

                resultado.classList.remove('d-none');
            }

            function carregarAlunos(instituicaoId) {
                alunoSelect.disabled = true;
                alunoSelect.innerHTML = '<option value="">Carregando atletas...</option>';
                btn.disabled = true;
                resultado.classList.add('d-none');

                fetch(`${alunosBase}/${instituicaoId}/alunos`, { credentials: 'same-origin' })
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

                fetch(`${resumoBase}/${encodeURIComponent(alunoSelect.value)}`, { credentials: 'same-origin' })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Falha ao carregar resumo');
                        }

                        return response.json();
                    })
                    .then(renderResultado)
                    .catch(() => {
                        vazio.textContent = 'Nao foi possivel carregar o resumo deste atleta.';
                        vazio.classList.remove('d-none');
                        resultado.classList.add('d-none');
                    });
            });

            tabButtons.forEach(button => {
                button.addEventListener('click', () => ativarAba(button.dataset.tabTarget));
            });

            ativarAba(isPrivilegiado ? 'resumo-panel-progresso' : 'resumo-panel-momento');
        });
    </script>
@endpush
