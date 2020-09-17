@component('mail::message')
<h1>Banco de Horas</h1>

<p>Relação dos funcionários que possuem banco e horas</p>

@component('mail::table')
<table>
    <thead>
        <th>Nome</th>
        <th class="th-w-25">Saldo</th>
    </thead>
    <tbody>
        <tr>
            <td>Eu</td>
            <td>00:00:00</td>
        </tr>
        <tr>
            <td>Eu</td>
            <td>00:00:00</td>
        </tr>
    </tbody>
</table>
@endcomponent

@component('mail::button', ['url' => 'https://day.mi7dev.com.br'])
Ir para MIDayWork
@endcomponent
@endcomponent
