<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Aluno</title>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            background-color: #426aee;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button{
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 5px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
    <body>       
        <div class="form-container">
            @if(session('success'))
                <div id="success-message" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <strong>Parabéns!</strong>
                    {{ session('success') }}
                </div>
                <script>
                    // Espera 3 segundos e remove a mensagem
                    setTimeout(function() {
                        let msg = document.getElementById('success-message');
                        if (msg) {
                            msg.remove();
                        }
                    }, 3000); // 3000 milissegundos = 3 segundos
                </script>
            @endif

            <h1>Inserir Aluno</h1>

            <form action="{{ route('aluno.store', [], true) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="nome">Nome do Aluno</label>
                <input type="text" id="nome" name="nome" required>

                <label for="arremesso">Arremesso</label>
                <input type="number" id="arremesso" name="arremesso" min="0" max="100" required>

                <label for="passe">Passe</label>
                <input type="number" id="passe" name="passe" min="0" max="100" required>

                <label for="marcacao">Marcação</label>
                <input type="number" id="marcacao" name="marcacao" min="0" max="100" required>

                <label for="finalizacao">Finalização</label>
                <input type="number" id="finalizacao" name="finalizacao" min="0" max="100" required>

                <label for="jogada">Jogada</label>
                <input type="number" id="jogada" name="jogada" min="0" max="100" required>

                <label for="dominio">Dominio</label>
                <input type="number" id="dominio" name="dominio" min="0" max="100" required>

                <button type="submit">Salvar</button>

                <button type="button" onclick="voltarParaAnalise()" style="background-color: #426aee; color: white; padding: 10px; width: 100%; border: none; border-radius: 5px; font-size: 16px; margin-top: 10px;">
                    Voltar
                </button>
                
                <script>
                    function voltarParaAnalise() {
                        window.location.href = "/analise"; // Redireciona diretamente para a página analise.blade
                    }
                </script>
            </form>
        </div>
    </body>
</html>
