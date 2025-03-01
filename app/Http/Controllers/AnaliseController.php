<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnaliseService;
use App\Models\Aluno;

class AnaliseController extends Controller {
    protected $analiseService;

    public function __construct(AnaliseService $analiseService) {
        $this->analiseService = $analiseService;
    }

    public function index() {

        $alunos = Aluno::select('id', 'nome')->distinct()->orderBy('nome', 'asc')->get();
        return view('analise', compact('alunos'));
    }

    public function show($id) {
        $analises = $this->analiseService->getUltimasAnalises($id);

        if ($analises->count() < 2) {
            return response()->json(['error' => 'Não há análises suficientes para comparação'], 400);
        }

        return response()->json($analises);
    }
}