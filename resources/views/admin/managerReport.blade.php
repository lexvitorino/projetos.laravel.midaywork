@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    <div class="summary-boxes">
        <div class="summary-box bg-primary">
            <i class="icon icofont-users"></i>
            <p class="title">Qtde de Funcionários</p>
            <h3 class="value">{{ $activeUsersCount }}</h3>
        </div>
        <div class="summary-box bg-danger">
            <i class="icon icofont-patient-bed"></i>
            <p class="title">Faltas</p>
            <h3 class="value">{{ count($absentUsers) }}</h3>
        </div>
        <div class="summary-box bg-success">
            <i class="icon icofont-sand-clock"></i>
            <p class="title">Horas no Mês</p>
            <h3 class="value">{{ $hoursInMonth }}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-{{ (count($absentUsers) > 0) ? '6' : '12' }}">
            @if(count($absentUsers) > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Faltosos do Dia</h4>
                    <p class="card-category mb-0">Relação dos funcionários que ainda não bateram o ponto</p>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <th>Nome</th>
                        </thead>
                        <tbody>
                            @foreach($absentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
        <div class="col-{{ (count($absentUsers) > 0) ? '6' : '12' }}">
            <div class="card mt-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-10">
                            <h4 class="card-title">Banco de Horas</h4>
                            <p class="card-category mb-0">Relação dos funcionários que possuem banco e horas</p>
                        </div>
                        <div class="col-2">
                            <form class="d-inline" method="POST" action="{{ route('managerReport') }}" onsubmit="return confirm('Enviar e-mail com os saldos de banco horas para o responsável?')">
                                @csrf
                                <input type="hidden" name="action" value="sendMail" />
                                    <i class="card-icon icofont-send-mail"></i>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <th>Nome</th>
                            <th class="th-w-25">Saldo</th>
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
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
