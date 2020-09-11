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
</main>

@endsection
