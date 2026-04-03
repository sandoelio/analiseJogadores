@if ($idades->isEmpty())
    <p class="relatorio-vazio">Nao ha atletas com idade preenchida para montar o relatorio.</p>
@else
    <div class="relatorio-tabela-wrap">
        <table class="relatorio-tabela">
            <thead>
                <tr>
                    <th class="coluna-projeto">Projeto</th>
                    @foreach ($idades as $idade)
                        <th>{{ $idade }}</th>
                    @endforeach
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($relatorio['linhas'] as $linha)
                    <tr>
                        <td class="coluna-projeto">{{ $linha['projeto'] }}</td>
                        @foreach ($idades as $idade)
                            <td>{{ $linha['idades'][$idade] ?? 0 }}</td>
                        @endforeach
                        <td>{{ $linha['total'] }}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td class="coluna-projeto">Somatoria</td>
                    @foreach ($idades as $idade)
                        <td>{{ $relatorio['somatoria_idades'][$idade] ?? 0 }}</td>
                    @endforeach
                    <td>{{ $relatorio['total_geral'] }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endif
