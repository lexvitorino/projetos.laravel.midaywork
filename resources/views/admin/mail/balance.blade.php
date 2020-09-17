@component('mail::message')
<h1>Banco de Horas</h1>

<p>Relação dos funcionários que possuem banco e horas</p>

@component('mail::table')
<table>
    <thead>
        <th>Nome</th>
        <th>Saldo</th>
    </thead>
    <tbody>
        @foreach($balances as $b)
        <tr>
            <td>{{ $b->name }}</td>
            <td class="text-right">{{ $b->balance }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endcomponent

@component('mail::button', ['url' => 'https://day.mi7dev.com.br'])
Ir para MIDayWork
@endcomponent
@endcomponent
