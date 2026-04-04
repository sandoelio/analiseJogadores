@extends('layouts.app')

@section('title', 'Plano de Acao do Atleta')

@push('styles')
    <style>
        .plano-shell { max-width: 1120px; margin: 0 auto; padding: 0.9rem 0 1.5rem; }
        @media (min-width: 992px) { .plano-shell { padding-top: 1.2rem; } }
        .plano-topo, .plano-resumo, .plano-form, .plano-lista { border: 1px solid #dbe1ec; border-radius: 1rem; background: #fff; box-shadow: 0 6px 18px rgba(26, 42, 80, 0.08); }
        .plano-topo { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 0.95rem 1rem; margin-bottom: 0.9rem; }
        .plano-chip { display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 0.55rem; padding: 0.3rem 0.65rem; border-radius: 999px; background: #eef3fb; color: #28365F; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; }
        .plano-title { margin: 0; color: #1f2d4f; font-size: 1.42rem; font-weight: 700; }
        .plano-texto { margin: 0.35rem 0 0; color: #5f6b85; font-size: 0.88rem; line-height: 1.45; }
        .plano-grid { display: grid; grid-template-columns: 320px minmax(0, 1fr); gap: 0.9rem; }
        .plano-resumo, .plano-form, .plano-lista { padding: 0.95rem; }
        .plano-resumo-grid { display: grid; gap: 0.7rem; }
        .plano-meta-linha { padding-bottom: 0.45rem; border-bottom: 1px solid #edf2f8; }
        .plano-meta-linha:last-child { padding-bottom: 0; border-bottom: none; }
        .plano-meta-rotulo { color: #5f6b85; font-size: 0.74rem; font-weight: 700; text-transform: uppercase; }
        .plano-meta-valor { margin-top: 0.18rem; color: #1f2d4f; font-weight: 700; }
        .plano-semaforo { display: inline-flex; align-items: center; justify-content: center; min-height: 32px; padding: 0.3rem 0.7rem; border-radius: 999px; font-size: 0.78rem; font-weight: 700; }
        .plano-semaforo-verde { background: #eaf7ee; color: #237a43; }
        .plano-semaforo-amarelo { background: #fff7e6; color: #c0821a; }
        .plano-semaforo-vermelho { background: #fff0ef; color: #c74e4e; }
        .plano-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem; }
        .plano-campo { display: grid; gap: 0.38rem; }
        .plano-campo-full { grid-column: 1 / -1; }
        .plano-label { color: #33405f; font-size: 0.82rem; font-weight: 700; }
        .plano-lista-cards { display: grid; gap: 0.75rem; max-height: 560px; overflow-y: auto; padding-right: 0.2rem; }
        .plano-item { border: 1px solid #e5eaf3; border-radius: 0.95rem; background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%); padding: 0.9rem; }
        .plano-item-topo { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.5rem; }
        .plano-item-titulo { margin: 0; color: #1f2d4f; font-size: 0.94rem; font-weight: 700; }
        .plano-badges { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.45rem; }
        .plano-badge { display: inline-flex; align-items: center; justify-content: center; min-height: 28px; padding: 0.22rem 0.6rem; border-radius: 999px; font-size: 0.74rem; font-weight: 700; }
        .plano-badge-status-aberto { background: #eef3fb; color: #28365F; }
        .plano-badge-status-em_andamento { background: #fff7e6; color: #c0821a; }
        .plano-badge-status-concluido { background: #eaf7ee; color: #237a43; }
        .plano-badge-prioridade-baixa { background: #edf2f8; color: #4c5f87; }
        .plano-badge-prioridade-media { background: #fff0e3; color: #d46317; }
        .plano-badge-prioridade-alta { background: #fff0ef; color: #c74e4e; }
        .plano-item-texto { margin: 0.45rem 0 0; color: #5f6b85; font-size: 0.84rem; line-height: 1.45; }
        .plano-item-form { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)) auto auto; gap: 0.55rem; align-items: end; margin-top: 0.8rem; }
        .plano-vazio { margin: 0; padding: 1rem; border-radius: 0.9rem; background: #f7f9fc; color: #5f6b85; font-weight: 600; }
        @media (max-width: 991.98px) { .plano-grid { grid-template-columns: 1fr; } .plano-item-form { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 767.98px) { .plano-topo { flex-direction: column; } .plano-topo .btn { width: 100%; } .plano-form-grid, .plano-item-form { grid-template-columns: 1fr; } }
        @media (max-width: 575.98px) { .plano-shell { padding-top: 0.45rem; } .plano-resumo, .plano-form, .plano-lista, .plano-topo { padding: 0.8rem; } .plano-title { font-size: 1.14rem; } .plano-texto { font-size: 0.82rem; line-height: 1.35; } }
    </style>
@endpush

@section('content')
    <div class="plano-shell">
        <div class="plano-topo">
            <div>
                <span class="plano-chip"><i class="bi bi-list-check"></i> Plano de acao</span>
                <h1 class="plano-title">{{ $aluno->nome }}</h1>
                <p class="plano-texto">Cadastre prioridades, acompanhe o andamento e marque as entregas concluidas sem sair do modulo tecnico.</p>
            </div>

            <a href="{{ route('aluno.index') }}" class="btn btn-secondary">Voltar para atletas</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show flash-auto flash-floating" data-auto-dismiss="4500" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show flash-auto flash-floating" data-auto-dismiss="5500" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="plano-grid">
            <div class="plano-resumo">
                <div class="plano-resumo-grid">
                    <div class="plano-meta-linha">
                        <div class="plano-meta-rotulo">Semaforo</div>
                        <div class="plano-meta-valor">
                            <span class="plano-semaforo plano-semaforo-{{ $semaforo['nivel'] }}">{{ $semaforo['rotulo'] }}</span>
                        </div>
                        <div class="plano-item-texto">{{ $semaforo['motivo'] }}</div>
                    </div>
                    <div class="plano-meta-linha">
                        <div class="plano-meta-rotulo">Idade</div>
                        <div class="plano-meta-valor">{{ $aluno->idade ?? '--' }} anos</div>
                    </div>
                    <div class="plano-meta-linha">
                        <div class="plano-meta-rotulo">Sexo</div>
                        <div class="plano-meta-valor">{{ $aluno->sexo ?? '--' }}</div>
                    </div>
                    <div class="plano-meta-linha">
                        <div class="plano-meta-rotulo">Media tecnica atual</div>
                        <div class="plano-meta-valor">{{ $mediaTecnica !== null ? number_format($mediaTecnica, 1, ',', '.') : '--' }}</div>
                    </div>
                    <div class="plano-meta-linha">
                        <div class="plano-meta-rotulo">Analises registradas</div>
                        <div class="plano-meta-valor">{{ $aluno->analises_count }}</div>
                    </div>
                    <div class="plano-meta-linha">
                        <div class="plano-meta-rotulo">Ultima analise</div>
                        <div class="plano-meta-valor">{{ $aluno->ultimaAnalise?->created_at?->format('d/m/Y') ?? '--' }}</div>
                    </div>
                </div>
            </div>

            <div>
                <div class="plano-form mb-3">
                    <h2 class="plano-title" style="font-size:1.05rem;">Novo plano</h2>
                    <form action="{{ route('tecnico.plano.store', $aluno) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="plano-form-grid">
                            <div class="plano-campo">
                                <label class="plano-label" for="titulo">Titulo</label>
                                <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo') }}" required>
                            </div>
                            <div class="plano-campo">
                                <label class="plano-label" for="prazo">Prazo</label>
                                <input type="date" name="prazo" id="prazo" class="form-control" value="{{ old('prazo') }}">
                            </div>
                            <div class="plano-campo">
                                <label class="plano-label" for="prioridade">Prioridade</label>
                                <select name="prioridade" id="prioridade" class="form-select">
                                    <option value="baixa">Baixa</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                            <div class="plano-campo">
                                <label class="plano-label" for="status">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="aberto" selected>Aberto</option>
                                    <option value="em_andamento">Em andamento</option>
                                    <option value="concluido">Concluido</option>
                                </select>
                            </div>
                            <div class="plano-campo plano-campo-full">
                                <label class="plano-label" for="descricao">Descricao</label>
                                <textarea name="descricao" id="descricao" rows="3" class="form-control">{{ old('descricao') }}</textarea>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Salvar plano</button>
                        </div>
                    </form>
                </div>

                <div class="plano-lista">
                    <h2 class="plano-title" style="font-size:1.05rem;">Planos cadastrados</h2>
                    @if ($aluno->planosAcao->isEmpty())
                        <p class="plano-vazio mt-3">Nenhum plano de acao cadastrado para este atleta ainda.</p>
                    @else
                        <div class="plano-lista-cards mt-3">
                            @foreach ($aluno->planosAcao as $plano)
                                <div class="plano-item">
                                    <div class="plano-item-topo">
                                        <div>
                                            <h3 class="plano-item-titulo">{{ $plano->titulo }}</h3>
                                            <div class="plano-badges">
                                                <span class="plano-badge plano-badge-status-{{ $plano->status }}">{{ str_replace('_', ' ', ucfirst($plano->status)) }}</span>
                                                <span class="plano-badge plano-badge-prioridade-{{ $plano->prioridade }}">{{ ucfirst($plano->prioridade) }}</span>
                                                <span class="plano-badge plano-badge-prioridade-baixa">Prazo: {{ $plano->prazo?->format('d/m/Y') ?? '--' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($plano->descricao)
                                        <p class="plano-item-texto">{{ $plano->descricao }}</p>
                                    @endif

                                    <form action="{{ route('tecnico.plano.update', $plano) }}" method="POST" class="plano-item-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="plano-campo">
                                            <label class="plano-label">Titulo</label>
                                            <input type="text" name="titulo" class="form-control" value="{{ $plano->titulo }}" required>
                                        </div>
                                        <div class="plano-campo">
                                            <label class="plano-label">Prazo</label>
                                            <input type="date" name="prazo" class="form-control" value="{{ $plano->prazo?->format('Y-m-d') }}">
                                        </div>
                                        <div class="plano-campo">
                                            <label class="plano-label">Prioridade</label>
                                            <select name="prioridade" class="form-select">
                                                <option value="baixa" @selected($plano->prioridade === 'baixa')>Baixa</option>
                                                <option value="media" @selected($plano->prioridade === 'media')>Media</option>
                                                <option value="alta" @selected($plano->prioridade === 'alta')>Alta</option>
                                            </select>
                                        </div>
                                        <div class="plano-campo">
                                            <label class="plano-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="aberto" @selected($plano->status === 'aberto')>Aberto</option>
                                                <option value="em_andamento" @selected($plano->status === 'em_andamento')>Em andamento</option>
                                                <option value="concluido" @selected($plano->status === 'concluido')>Concluido</option>
                                            </select>
                                        </div>
                                        <div class="plano-campo" style="grid-column: 1 / -1;">
                                            <label class="plano-label">Descricao</label>
                                            <textarea name="descricao" rows="2" class="form-control">{{ $plano->descricao }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-outline-primary">Atualizar</button>
                                    </form>

                                    <form action="{{ route('tecnico.plano.destroy', $plano) }}" method="POST" class="mt-2" onsubmit="return confirm('Deseja remover este plano de acao?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Excluir plano</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
