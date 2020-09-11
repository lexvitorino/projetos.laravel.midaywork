@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    <div class="card">
        <div class="card-header">
            <h3>{{ $today }}</h3>
            <p class="mb-0">Os batimentos efetuados hoje</p>
        </div>
        <div class="card-body">
            <div class="d-flex m-5 justify-content-around">
                <span class="record">Entrada 1: {{ $workingHours->time1 ?? '--:--:--' }}</span>
                <span class="record">Saída 1: {{ $workingHours->time2 ?? '--:--:--' }}</span>
            </div>
            <div class="d-flex m-5 justify-content-around">
                <span class="record">Entrada 2: {{ $workingHours->time3 ?? '--:--:--' }}</span>
                <span class="record">Saída 2: {{ $workingHours->time4 ?? '--:--:--' }}</span>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-center">
            <form class="d-inline" method="POST" action="{{ route('dayRecord') }}" onsubmit="return confirm('Confirma apontamento?')">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="icofont-check mr-1"></i>
                    Bater o Ponto
                </button>
            </form>
        </div>
    </div>

    <form class="mt-5" action="{{ route('dayRecord') }}" method="post">
        @csrf
        <div class="input-group no-border">
            <input type="date" name="forcedDate" class="form-control mr-3" />
            <input type="text" name="forcedTime" class="form-control" placeholder="Informe a hora para apontar manualmente">
            <button type="submit" class="btn btn-danger ml-3">
                Simular Ponto
            </button>
        </div>
    </form>

</main>
@endsection
