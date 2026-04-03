@if ($idades->isEmpty())
    <p class="relatorio-vazio">Nao ha atletas com idade preenchida para montar o relatorio.</p>
@else
    <div class="relatorio-tabela-wrap">
        <table class="relatorio-tabela">
            <thead>
                <tr>
                    @foreach ($idades as $idade)
                        <th>{{ $idade }}</th>
                    @endforeach
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    @foreach ($idades as $idade)
                        <td>{{ $relatorio['idades'][$idade] ?? 0 }}</td>
                    @endforeach
                    <td>{{ $relatorio['total'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endif
