{{-- resources/views/aluno/habilidade.blade.php --}}
@extends('layouts.app')

@section('title', 'Atualizar Atleta')

@push('styles')
    <style>
        /* Readonly sem editar */
        input[readonly] {
            background-color: #e9ecef;
            opacity: 1;
            cursor: not-allowed;
        }

        /* Cores do header e botão */
        .bg-navbar-blue { background-color: #28365F !important; color: #fff; }
        .btn-navbar-blue {
            background-color: #28365F;
            border-color: #28365F;
            color: #fff;
        }
        .btn-navbar-blue:hover {
            background-color: #28365F;
            border-color: #28365F;
        }

        /* -------------------------------
           Scroll interno em mobile
           ------------------------------- */
        @media (max-width: 576px) {
            .habilidade-card-body {
                max-height: calc(100vh - 160px); /* header + footer + padding */
                overflow-y: auto;
                padding-right: 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center mt-4 mb-4">
        <div class="col-12 col-md-8 col-lg-6">
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

                        <div class="row g-3">
                            {{-- Seleção do atleta (full width) --}}
                            <div class="col-12">
                                <label for="aluno_select" class="form-label">Selecione o Atleta</label>
                                <select id="aluno_select" name="aluno_id" class="form-select" required>
                                    <option selected disabled>-- selecione --</option>
                                    @foreach ($alunos as $a)
                                        <option value="{{ $a->id }}">{{ $a->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Estatísticas em duas colunas para reduzir altura --}}
                            @foreach (['arremesso','passe','marcacao','bandeja','rebote','dominio'] as $campo)
                                <div class="col-6">
                                    <label for="{{ $campo }}" class="form-label">
                                        {{ ucfirst($campo==='dominio'?'Domínio de Bola':$campo) }}
                                    </label>
                                    <input
                                        type="number"
                                        id="{{ $campo }}"
                                        name="{{ $campo }}"
                                        class="form-control"
                                        value="1"
                                        min="0"
                                        max="10"
                                        readonly
                                    >
                                </div>
                            @endforeach

                            {{-- Botões: empilhados no mobile, lado a lado no desktop --}}
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                    <button type="submit" class="btn btn-navbar-blue flex-md-grow-1">
                                        Atualizar Atleta
                                    </button>
                                    <a href="{{ route('tecnico.dashboard') }}"
                                       class="btn btn-secondary flex-md-grow-1">
                                        Cancelar
                                    </a>
                                </div>
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
            select.addEventListener('change', () => {
                const id = select.value;
                fetch(`/aluno/${id}/ultima-analise`)
                    .then(res => res.json())
                    .then(data => {
                        ['arremesso','passe','marcacao','bandeja','rebote','dominio'].forEach(campo => {
                            const inp = document.getElementById(campo);
                            inp.value = data[campo];
                            inp.removeAttribute('readonly');
                        });
                    })
                    .catch(() => alert('Não foi possível carregar a última análise.'));
            });
        });
    </script>
@endpush
