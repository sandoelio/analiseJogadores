<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioStoreRequest;
use App\Http\Requests\UsuarioUpdateRequest;
use App\Models\User;
use App\Models\Instituicao;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\CheckSession::class);
        $this->middleware(\App\Http\Middleware\CheckAdmin::class);
    }

    public function index()
    {
        $agent = new Agent();
        $perPage = $agent->isMobile() ? 3 : 5;
        $usuarios = User::where('is_admin', false)->with('instituicao')->paginate($perPage);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('usuarios.create', compact('instituicoes'));
    }

    public function store(UsuarioStoreRequest $request)
    {
        // 1) Validação incluindo credenciais de atleta
        $data = $request->validated();

        // 2) Cria ou atualiza a Instituição
        $instituicao = Instituicao::updateOrCreate(
            ['nome' => $data['instituicao_nome']],
            [
                'athlete_email'    => $data['athlete_email'],
                'athlete_password' => $data['athlete_password'], // mutator faz o Hash
            ]
        );

        // 3) Cria o usuário técnico
        User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'is_admin'       => false,
            'instituicao_id' => $instituicao->id,
        ]);

        return redirect()
            ->route('usuarios.create')
            ->with('success', 'Usuário e credenciais de atleta cadastrados com sucesso!');
    }

    public function edit(User $usuario)
    {
        $instituicoes = Instituicao::orderBy('nome')->get();
        return view('usuarios.edit', compact('usuario', 'instituicoes'));
    }

    public function update(UsuarioUpdateRequest $request, User $usuario)
    {
        // 1) Validação incluindo credenciais de atleta
        $data = $request->validated();

        // 2) Atualiza o usuário técnico
        $usuario->name  = $data['name'];
        $usuario->email = $data['email'];
        if (!empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }
        $usuario->save();

        // 3) Atualiza as credenciais de atleta na Instituição
        $usuario->instituicao->update([
            'athlete_email'    => $data['athlete_email'],
            'athlete_password' => $data['athlete_password'] ?? $usuario->instituicao->athlete_password,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário e credenciais de atleta atualizados com sucesso!');
    }

    public function destroy(User $usuario)
    {
        DB::transaction(function () use ($usuario) {
            $instituicao = $usuario->instituicao;

            $usuario->delete();
            if ($instituicao) {
                $instituicao->delete();
                return;
            }

            $usuario->delete();
        });

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}
