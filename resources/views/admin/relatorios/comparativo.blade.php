@extends('layouts.app')

@section('title', 'Comparativo entre Instituicoes')

@push('styles')
    <style>
        .comparativo-shell {
            max-width: 1220px;
            width: 100%;
            margin: 0 auto;
            padding-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .comparativo-shell {
                padding-top: 1.2rem;
            }
        }

        .comparativo-topo,
        .comparativo-filtro,
        .comparativo-resumo,
        .comparativo-secao {
            border: 1px solid #dbe1ec;
            border-radius: 0.9rem;
            background: #fff;
            box-shadow: 0 4px 14px rgba(26, 42, 80, 0.06);
        }

        .comparativo-topo {
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
        }

        .comparativo-chip {
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

        .comparativo-titulo {
            margin: 0;
            color: #1e2b4f;
            font-size: 1.45rem;
            font-weight: 700;
        }

        .comparativo-texto {
            margin: 0.35rem 0 0;
            color: #5f6b85;
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .comparativo-filtro {
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
        }

        .comparativo-filtro-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto auto;
            gap: 0.75rem;
            align-items: end;
        }

        .comparativo-label {
            display: block;
            margin-bottom: 0.4rem;
            color: #33405f;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .comparativo-select {
            min-height: 46px;
            border-radius: 0.85rem;
            border-color: #dbe1ec;
        }

        .comparativo-btn {
            min-height: 44px;
            padding: 0.6rem 1rem;
            border-radius: 0.85rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .comparativo-btn-principal {
            background: #28365F;
            border-color: #28365F;
            color: #fff;
        }

        .comparativo-btn-principal:hover {
            background: #1f2d4f;
            border-color: #1f2d4f;
            color: #fff;
        }

        .comparativo-alerta {
            margin-bottom: 1rem;
            border-radius: 0.9rem;
        }

        .comparativo-resumo {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .comparativo-resumo-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .comparativo-inst-card {
            padding: 0.95rem;
            border: 1px solid #dbe1ec;
            border-radius: 0.95rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .comparativo-inst-nome {
            margin: 0;
            color: #1f2d4f;
            font-size: 1rem;
            font-weight: 700;
        }

        .comparativo-inst-meta {
            margin-top: 0.7rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.7rem;
        }

        .comparativo-inst-bloco {
            padding: 0.75rem 0.8rem;
            border: 1px solid #e5eaf3;
            border-radius: 0.85rem;
            background: #fff;
        }

        .comparativo-inst-numero {
            color: #1f2d4f;
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1;
        }

        .comparativo-inst-label {
            margin-top: 0.3rem;
            color: #5f6b85;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .comparativo-secao {
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .comparativo-secao-header {
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #edf2f8;
        }

        .comparativo-secao-titulo {
            margin: 0;
            color: #1f2d4f;
            font-size: 1.02rem;
            font-weight: 700;
        }

        .comparativo-secao-texto {
            margin: 0.25rem 0 0;
            color: #5f6b85;
            font-size: 0.86rem;
        }

        .comparativo-tabela-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .comparativo-tabela {
            width: 100%;
            min-width: 760px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .comparativo-tabela th,
        .comparativo-tabela td {
            padding: 0.8rem 0.9rem;
            border: 1px solid #d8dee9;
            text-align: left;
        }

        .comparativo-tabela th {
            background: #223154;
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .comparativo-tabela td {
            background: #fff;
            color: #263248;
            font-size: 0.88rem;
        }

        .comparativo-tabela td:first-child {
            font-weight: 700;
            background: #f8fafd;
        }

        .comparativo-mobile {
            display: none;
            padding: 0.9rem;
        }

        .comparativo-mobile-card {
            border: 1px solid #e5eaf3;
            border-radius: 0.95rem;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            padding: 0.85rem 0.9rem;
        }

        .comparativo-mobile-card + .comparativo-mobile-card {
            margin-top: 0.7rem;
        }

        .comparativo-mobile-titulo {
            margin: 0 0 0.55rem;
            color: #1f2d4f;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .comparativo-mobile-linha {
            display: grid;
            gap: 0.25rem;
            padding: 0.55rem 0;
            border-top: 1px solid #edf2f8;
        }

        .comparativo-mobile-linha:first-of-type {
            border-top: none;
            padding-top: 0;
        }

        .comparativo-mobile-campo {
            color: #5f6b85;
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .comparativo-mobile-valor {
            color: #1f2d4f;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .comparativo-vazio {
            margin: 0;
            padding: 1rem 1.05rem;
            border-radius: 0.95rem;
            background: #f7f9fc;
            color: #5f6b85;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .comparativo-filtro-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .comparativo-resumo-grid {
                grid-template-columns: 1fr;
            }

            .comparativo-inst-meta {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 575.98px) {
            .comparativo-shell {
                padding-top: 0.35rem;
            }

            .comparativo-filtro-grid {
                grid-template-columns: 1fr;
            }

            .comparativo-tabela-wrap {
                display: none;
            }

            .comparativo-mobile {
                display: block;
            }
        }
    </style>
@endpush

@section('content')
    <div class="comparativo-shell">
        <div class="comparativo-topo">
            <span class="comparativo-chip">
                <i class="bi bi-arrow-left-right"></i>
                Comparativo
            </span>
            <h1 class="comparativo-titulo">Comparativo entre instituicoes</h1>
            <p class="comparativo-texto">
                Compare dois projetos lado a lado pelo volume cadastral e pelo desempenho medio da ultima analise dos atletas.
            </p>
        </div>

        @if ($errors->any())
            <div class="alert alert-warning comparativo-alerta">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="comparativo-filtro">
            <form method="GET" action="{{ route('admin.relatorios.comparativo') }}">
                <div class="comparativo-filtro-grid">
                    <div>
                        <label for="instituicao_a_id" class="comparativo-label">Instituicao A</label>
                        <select id="instituicao_a_id" name="instituicao_a_id" class="form-select comparativo-select" required>
                            <option value="">Selecione a primeira instituicao</option>
                            @foreach ($instituicoes as $instituicao)
                                <option value="{{ $instituicao->id }}" @selected((int) request('instituicao_a_id') === (int) $instituicao->id)>
                                    {{ $instituicao->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="instituicao_b_id" class="comparativo-label">Instituicao B</label>
                        <select id="instituicao_b_id" name="instituicao_b_id" class="form-select comparativo-select" required>
                            <option value="">Selecione a segunda instituicao</option>
                            @foreach ($instituicoes as $instituicao)
                                <option value="{{ $instituicao->id }}" @selected((int) request('instituicao_b_id') === (int) $instituicao->id)>
                                    {{ $instituicao->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn comparativo-btn comparativo-btn-principal">
                        Comparar
                    </button>

                    <a href="{{ route('admin.relatorios') }}" class="btn btn-secondary comparativo-btn">
                        Voltar
                    </a>
                </div>
            </form>
        </div>

        @if ($comparativo)
            <div class="comparativo-resumo">
                <div class="comparativo-resumo-grid">
                    @foreach (['instituicao_a', 'instituicao_b'] as $chaveInstituicao)
                        @php $dados = $comparativo[$chaveInstituicao]; @endphp
                        <div class="comparativo-inst-card">
                            <h2 class="comparativo-inst-nome">{{ $dados['nome'] }}</h2>

                            <div class="comparativo-inst-meta">
                                <div class="comparativo-inst-bloco">
                                    <div class="comparativo-inst-numero">{{ $dados['volume']['total_atletas'] }}</div>
                                    <div class="comparativo-inst-label">Atletas</div>
                                </div>

                                <div class="comparativo-inst-bloco">
                                    <div class="comparativo-inst-numero">{{ $dados['desempenho']['atletas_avaliados'] }}</div>
                                    <div class="comparativo-inst-label">Avaliados</div>
                                </div>

                                <div class="comparativo-inst-bloco">
                                    <div class="comparativo-inst-numero">{{ $dados['desempenho']['media_tecnica'] }}</div>
                                    <div class="comparativo-inst-label">Media tecnica</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <section class="comparativo-secao">
                <div class="comparativo-secao-header">
                    <h2 class="comparativo-secao-titulo">Comparativo de volume</h2>
                    <p class="comparativo-secao-texto">
                        Leitura administrativa do cadastro, da distribuicao e das pendencias principais das instituicoes selecionadas.
                    </p>
                </div>

                <div class="comparativo-tabela-wrap">
                    <table class="comparativo-tabela">
                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th>{{ $comparativo['instituicao_a']['nome'] }}</th>
                                <th>{{ $comparativo['instituicao_b']['nome'] }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comparativo['linhas_volume'] as $linha)
                                <tr>
                                    <td>{{ $linha['titulo'] }}</td>
                                    <td>{{ $comparativo['instituicao_a']['volume'][$linha['chave']] }}</td>
                                    <td>{{ $comparativo['instituicao_b']['volume'][$linha['chave']] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="comparativo-mobile">
                    <div class="comparativo-mobile-card">
                        <h3 class="comparativo-mobile-titulo">Volume</h3>
                        @foreach ($comparativo['linhas_volume'] as $linha)
                            <div class="comparativo-mobile-linha">
                                <div class="comparativo-mobile-campo">{{ $linha['titulo'] }}</div>
                                <div class="comparativo-mobile-valor">{{ $comparativo['instituicao_a']['nome'] }}: {{ $comparativo['instituicao_a']['volume'][$linha['chave']] }}</div>
                                <div class="comparativo-mobile-valor">{{ $comparativo['instituicao_b']['nome'] }}: {{ $comparativo['instituicao_b']['volume'][$linha['chave']] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="comparativo-secao">
                <div class="comparativo-secao-header">
                    <h2 class="comparativo-secao-titulo">Comparativo de desempenho</h2>
                    <p class="comparativo-secao-texto">
                        Medias consolidadas com base na ultima analise registrada por atleta, para leitura gerencial e nao individual.
                    </p>
                </div>

                <div class="comparativo-tabela-wrap">
                    <table class="comparativo-tabela">
                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th>{{ $comparativo['instituicao_a']['nome'] }}</th>
                                <th>{{ $comparativo['instituicao_b']['nome'] }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comparativo['linhas_desempenho'] as $linha)
                                <tr>
                                    <td>{{ $linha['titulo'] }}</td>
                                    <td>{{ $comparativo['instituicao_a']['desempenho'][$linha['chave']] }}</td>
                                    <td>{{ $comparativo['instituicao_b']['desempenho'][$linha['chave']] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="comparativo-mobile">
                    <div class="comparativo-mobile-card">
                        <h3 class="comparativo-mobile-titulo">Desempenho</h3>
                        @foreach ($comparativo['linhas_desempenho'] as $linha)
                            <div class="comparativo-mobile-linha">
                                <div class="comparativo-mobile-campo">{{ $linha['titulo'] }}</div>
                                <div class="comparativo-mobile-valor">{{ $comparativo['instituicao_a']['nome'] }}: {{ $comparativo['instituicao_a']['desempenho'][$linha['chave']] }}</div>
                                <div class="comparativo-mobile-valor">{{ $comparativo['instituicao_b']['nome'] }}: {{ $comparativo['instituicao_b']['desempenho'][$linha['chave']] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @else
            <p class="comparativo-vazio">
                Selecione duas instituicoes para abrir o comparativo gerencial de volume e desempenho.
            </p>
        @endif
    </div>
@endsection
