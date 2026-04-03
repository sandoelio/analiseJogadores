@if ($idades->isEmpty())
    <p class="relatorio-vazio">Nao ha atletas com idade preenchida para montar o relatorio.</p>
@else
    <div class="relatorio-desktop">
        <div class="relatorio-tabela-wrap">
            <table class="relatorio-tabela">
                <thead>
                    <tr>
                        <th class="relatorio-th-fixo">Idade</th>
                        @foreach ($idades as $idade)
                            <th>{{ $idade }}</th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="relatorio-td-fixo">Atletas</td>
                        @foreach ($idades as $idade)
                            <td>{{ $relatorio['idades'][$idade] ?? 0 }}</td>
                        @endforeach
                        <td>{{ $relatorio['total'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="relatorio-mobile">
        <table class="relatorio-mobile-tabela">
            <thead>
                <tr>
                    <th>Idade</th>
                    <th>Atletas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($idades as $idade)
                    <tr>
                        <td>{{ $idade }}</td>
                        <td>{{ $relatorio['idades'][$idade] ?? 0 }}</td>
                    </tr>
                @endforeach
                <tr class="relatorio-mobile-total">
                    <td>Total</td>
                    <td>{{ $relatorio['total'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endif
