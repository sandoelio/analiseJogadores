@extends('layouts.app')

@section('title', 'Ranking Interno')

@push('styles')
    <style>
        .ranking-shell { max-width: 1180px; margin: 0 auto; padding: 0.9rem 0 1.5rem; }
        @media (min-width: 992px) { .ranking-shell { padding-top: 1.2rem; } }
        .ranking-topo, .ranking-filtro, .ranking-card { border: 1px solid #dbe1ec; border-radius: 1rem; background: #fff; box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08); }
        .ranking-topo { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 0.95rem 1rem; margin-bottom: 0.9rem; }
        .ranking-chip { display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 0.55rem; padding: 0.3rem 0.65rem; border-radius: 999px; background: #eef3fb; color: #28365F; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; }
        .ranking-title { margin: 0; color: #1f2d4f; font-size: 1.42rem; font-weight: 700; }
        .ranking-texto { margin: 0.35rem 0 0; color: #5f6b85; font-size: 0.88rem; line-height: 1.45; }
        .ranking-filtro { padding: 0.9rem 1rem; margin-bottom: 0.9rem; }
        .ranking-filtro-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto auto; gap: 0.75rem; align-items: end; }
        .ranking-label { display: block; margin-bottom: 0.4rem; color: #33405f; font-size: 0.84rem; font-weight: 700; }
        .ranking-select { min-height: 44px; border-radius: 0.85rem; border-color: #dbe1ec; }
        .ranking-btn { min-height: 42px; padding: 0.6rem 1rem; border-radius: 0.85rem; font-weight: 700; white-space: nowrap; }
        .ranking-btn-principal { background: #28365F; border-color: #28365F; color: #fff; }
        .ranking-btn-principal:hover { background: #1f2d4f; border-color: #1f2d4f; color: #fff; }
        .ranking-tabela-wrap { overflow-x: auto; }
        .ranking-tabela { width: 100%; min-width: 920px; border-collapse: separate; border-spacing: 0; }
        .ranking-tabela th, .ranking-tabela td { padding: 0.8rem 0.9rem; border: 1px solid #d8dee9; vertical-align: middle; }
        .ranking-tabela th { background: #223154; color: #fff; font-size: 0.82rem; font-weight: 700; text-transform: uppercase; white-space: nowrap; }
        .ranking-tabela td { background: #fff; color: #263248; font-size: 0.88rem; }
        .ranking-tabela td:first-child { font-weight: 700; }
        .ranking-semaforo { display: inline-flex; align-items: center; justify-content: center; min-height: 32px; padding: 0.3rem 0.7rem; border-radius: 999px; font-size: 0.78rem; font-weight: 700; }
        .ranking-semaforo-verde { background: #eaf7ee; color: #237a43; }
        .ranking-semaforo-amarelo { background: #fff7e6; color: #c0821a; }
        .ranking-semaforo-vermelho { background: #fff0ef; color: #c74e4e; }
        .ranking-motivo { display: block; margin-top: 0.2rem; color: #6a748a; font-size: 0.74rem; }
        .ranking-mobile { display: none; padding: 0.8rem; }
        .ranking-mobile-card { border: 1px solid #e5eaf3; border-radius: 0.95rem; background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); padding: 0.85rem; }
        .ranking-mobile-card + .ranking-mobile-card { margin-top: 0.7rem; }
        .ranking-mobile-topo { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.7rem; margin-bottom: 0.45rem; }
        .ranking-mobile-nome { margin: 0; color: #1f2d4f; font-size: 0.95rem; font-weight: 700; }
        .ranking-mobile-meta { color: #5f6b85; font-size: 0.8rem; }
        .ranking-mobile-linha { display: grid; grid-template-columns: 110px 1fr; gap: 0.5rem; padding-top: 0.35rem; font-size: 0.8rem; line-height: 1.35; }
        .ranking-mobile-rotulo { color: #5f6b85; font-weight: 700; }
        .ranking-vazio { margin: 0; padding: 1rem; border-radius: 0.9rem; background: #f7f9fc; color: #5f6b85; font-weight: 600; }
        @media (max-width: 991.98px) { .ranking-filtro-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 767.98px) { .ranking-topo { flex-direction: column; } .ranking-topo .btn { width: 100%; } .ranking-filtro-grid { grid-template-columns: 1fr; } }
        @media (max-width: 575.98px) { .ranking-shell { padding-top: 0.45rem; } .ranking-tabela-wrap { display: none; } .ranking-mobile { display: block; } }
    </style>
@endpush

@section('content')
    <div class="ranking-shell">
        <div class="ranking-topo">
            <div>
                <span class="ranking-chip"><i class="bi bi-trophy"></i> Ranking</span>
                <p class="ranking-texto">
                    Ordena os atletas pela media tecnica atual e mostra o semaforo operacional junto da quantidade de planos em aberto.
                </p>
            </div>

            <a href="{{ route('tecnico.relatorios') }}" class="btn btn-secondary ranking-btn">Voltar</a>
        </div>

        <div class="ranking-filtro">
            <form method="GET" action="{{ route('tecnico.ranking') }}">
                <div class="ranking-filtro-grid">
                    <div>
                        <label for="sexo" class="ranking-label">Sexo</label>
                        <select name="sexo" id="sexo" class="form-select ranking-select">
                            <option value="">Todos</option>
                            <option value="Masculino" @selected($sexoSelecionado === 'Masculino')>Masculino</option>
                            <option value="Feminino" @selected($sexoSelecionado === 'Feminino')>Feminino</option>
                        </select>
                    </div>

                    <div>
                        <label for="idade" class="ranking-label">Idade</label>
                        <select name="idade" id="idade" class="form-select ranking-select">
                            <option value="">Todas</option>
                            @foreach ($idades as $idade)
                                <option value="{{ $idade }}" @selected($idadeSelecionada === $idade)>{{ $idade }} anos</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn ranking-btn ranking-btn-principal">Filtrar</button>
                    <a href="{{ route('tecnico.ranking') }}" class="btn btn-secondary ranking-btn">Limpar</a>
                </div>
            </form>
        </div>

        <div class="ranking-card">
            @if ($ranking->isEmpty())
                <p class="ranking-vazio">Nao ha atletas com analise suficiente para montar o ranking com os filtros atuais.</p>
            @else
                <div class="ranking-tabela-wrap">
                    <table class="ranking-tabela">
                        <thead>
                            <tr>
                                <th>Posicao</th>
                                <th>Atleta</th>
                                <th>Idade</th>
                                <th>Media tecnica</th>
                                <th>Semaforo</th>
                                <th>Planos abertos</th>
                                <th>Plano de acao</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranking as $linha)
                                <tr>
                                    <td>{{ $linha['posicao'] }}</td>
                                    <td>{{ $linha['aluno']->nome }}</td>
                                    <td>{{ $linha['aluno']->idade ?? '--' }}</td>
                                    <td>{{ $linha['media_tecnica_formatada'] }}</td>
                                    <td>
                                        <span class="ranking-semaforo ranking-semaforo-{{ $linha['semaforo']['nivel'] }}">
                                            {{ $linha['semaforo']['rotulo'] }}
                                        </span>
                                        <span class="ranking-motivo">{{ $linha['semaforo']['motivo'] }}</span>
                                    </td>
                                    <td>{{ $linha['planos_abertos'] }}</td>
                                    <td>
                                        <a href="{{ route('tecnico.plano.show', $linha['aluno']) }}" class="btn btn-outline-primary btn-sm">
                                            Abrir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="ranking-mobile">
                    @foreach ($ranking as $linha)
                        <div class="ranking-mobile-card">
                            <div class="ranking-mobile-topo">
                                <div>
                                    <h3 class="ranking-mobile-nome">#{{ $linha['posicao'] }} {{ $linha['aluno']->nome }}</h3>
                                    <div class="ranking-mobile-meta">{{ $linha['aluno']->idade ?? '--' }} anos</div>
                                </div>

                                <span class="ranking-semaforo ranking-semaforo-{{ $linha['semaforo']['nivel'] }}">
                                    {{ $linha['semaforo']['rotulo'] }}
                                </span>
                            </div>

                            <div class="ranking-mobile-linha">
                                <span class="ranking-mobile-rotulo">Media tecnica</span>
                                <span>{{ $linha['media_tecnica_formatada'] }}</span>
                            </div>
                            <div class="ranking-mobile-linha">
                                <span class="ranking-mobile-rotulo">Motivo</span>
                                <span>{{ $linha['semaforo']['motivo'] }}</span>
                            </div>
                            <div class="ranking-mobile-linha">
                                <span class="ranking-mobile-rotulo">Planos abertos</span>
                                <span>{{ $linha['planos_abertos'] }}</span>
                            </div>
                            <div class="ranking-mobile-linha">
                                <span class="ranking-mobile-rotulo">Plano de acao</span>
                                <span><a href="{{ route('tecnico.plano.show', $linha['aluno']) }}" class="btn btn-outline-primary btn-sm">Abrir</a></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
