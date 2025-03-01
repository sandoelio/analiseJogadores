<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análise de Habilidades</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #426aee;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        canvas {
            max-width: 100%;
            margin-top: 10px;
        }
        .thumbnail {    
                display: flex;
                display: block;
                margin: 0 auto;
                max-width: 50%;
                height: auto;
            }
    </style>
</head>
<body>

    <div class="container">
        <img src="{{ asset('imagem/slogan.png') }}" alt="Imagem" class="thumbnail">
        <h1>Análise de Habilidades</h1>
        <select id="alunoSelect" onchange="carregarGrafico()">
            <option value="">Selecione um aluno</option>
            @foreach($alunos as $aluno)
                <option value="{{ $aluno->id }}">{{ $aluno->nome }}</option>
            @endforeach
        </select>

        <canvas id="graficoComparativo"></canvas>
    </div>

    <script>
        async function carregarGrafico() {
            const alunoId = document.getElementById('alunoSelect').value;
            if (!alunoId) return;

            const resposta = await fetch(`/alunos/${alunoId}/analises`);
            const dados = await resposta.json();

            if (dados.length < 2) {
                alert("Não há análises suficientes para comparação.");
                return;
            }

            const analiseAtual = dados[0];
            const analiseAnterior = dados[1];
            
            const ctx = document.getElementById('graficoComparativo').getContext('2d');
            
            // Removendo gráfico anterior se existir
            if (window.grafico) {
                window.grafico.destroy();
            }

            window.grafico = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Arremesso', 'Passe', 'Marcação', 'Finalização', 'Jogada','dominio.B'],
                    datasets: [
                        {
                            label: 'Última Análise',
                            data: [
                                analiseAnterior.arremesso,
                                analiseAnterior.passe,
                                analiseAnterior.marcacao,
                                analiseAnterior.finalizacao,
                                analiseAnterior.jogada,
                                analiseAnterior.dominio
                            ],
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        },
                        {
                            label: 'Análise Atual',
                            data: [
                                analiseAtual.arremesso,
                                analiseAtual.passe,
                                analiseAtual.marcacao,
                                analiseAtual.finalizacao,
                                analiseAtual.jogada,
                                analiseAtual.dominio
                            ],
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        }
                    ]
                }
            });
        }
    </script>

</body>
</html>
