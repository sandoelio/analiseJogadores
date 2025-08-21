{{-- view Alunos/habilidade.blade.php --}}
@extends('layouts.app')

@section('title', 'Atualizar Atleta')

@push('styles')
    <style>
        input[readonly] {
            background-color: #e9ecef;
            opacity: 1;
            cursor: not-allowed;
        }

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
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-navbar-blue text-center">
                    <h5 class="mb-0">
                        Atualizar Atleta
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('aluno.habilidade.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="aluno_select" class="form-label">Selecione o Atleta</label>
                            <select id="aluno_select" name="aluno_id" class="form-select" required>
                                <option selected disabled>-- selecione --</option>
                                @foreach ($alunos as $a)
                                    <option value="{{ $a->id }}">{{ $a->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        @foreach (['arremesso', 'passe', 'marcacao', 'bandeja', 'jogada', 'dominio'] as $campo)
                            <div class="mb-3">
                                <label for="{{ $campo }}" class="form-label">
                                    {{ ucfirst($campo === 'dominio' ? 'Domínio de Bola' : $campo) }}
                                </label>
                                <input type="number" id="{{ $campo }}" name="{{ $campo }}"
                                    class="form-control" value="1" min="0" max="10" readonly>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-navbar-blue">Atualizar Atleta</button>
                            <a href="{{ route('aluno.dashboard') }}" class="btn btn-secondary">Cancelar</a>
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
                        // Preenche os campos de estatística
                        ['arremesso', 'passe', 'marcacao', 'bandeja', 'jogada', 'dominio'].forEach(
                            campo => {
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
