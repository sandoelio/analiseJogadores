@extends('layouts.app')

@section('title', 'Comparar Atletas')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4">Simular Duelo 1x1 de Basquete</h2>

        <form method="POST" action="{{ route('comparar.narrar') }}">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="aluno1_id" class="form-label">Atleta 1</label>
                    <select name="aluno1_id" id="aluno1_id" class="form-select" required>
                        <option value="" disabled selected>Selecione o jogador</option>
                        @foreach ($instituicoes as $inst)
                            <optgroup label="{{ $inst->nome }}">
                                @foreach ($inst->alunos as $aluno)
                                    <option value="{{ $aluno->id }}">
                                        {{ $aluno->nome }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="aluno2_id" class="form-label">Atleta 2</label>
                    <select name="aluno2_id" id="aluno2_id" class="form-select" required>
                        <option value="" disabled selected>Selecione o jogador</option>
                        @foreach ($instituicoes as $inst)
                            <optgroup label="{{ $inst->nome }}">
                                @foreach ($inst->alunos as $aluno)
                                    <option value="{{ $aluno->id }}">
                                        {{ $aluno->nome }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-navbar-blue">
                <i class="bi bi-joystick me-1"></i> Narrar Partida
            </button>
        </form>
    </div>
@endsection
