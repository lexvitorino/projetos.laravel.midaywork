@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    <div class="summary-boxes">
        <div class="summary-box bg-primary">
            <i class="icon icofont-users"></i>
            <p class="title">@lang('custom.number-of-employees')</p>
            <h3 class="value">{{ $activeUsersCount }}</h3>
        </div>
        <div class="summary-box bg-danger">
            <i class="icon icofont-patient-bed"></i>
            <p class="title">@lang('custom.fouls')</p>
            <h3 class="value">{{ count($absentUsers) }}</h3>
        </div>
        <div class="summary-box bg-success">
            <i class="icon icofont-sand-clock"></i>
            <p class="title">@lang('custom.hours-in-month')</p>
            <h3 class="value">{{ $hoursInMonth }}</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-{{ (count($absentUsers) > 0) ? '6' : '12' }}">
            @if(count($absentUsers) > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">@lang('custom.missing-of-the-day')</h4>
                    <p class="card-category mb-0">@lang('custom.list-of-employees-who-have-not-yet-hit-the-spot')</p>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <th>@lang('custom.name')</th>
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
                        <div class="col-9">
                            <h4 class="card-title">@lang('custom.bank-of-hours')</h4>
                            <p class="card-category mb-0">@lang('custom.list-of-employees-who-have-an-hour-bank')</p>
                        </div>
                        <div class="col-3 text-right">
                            <form class="d-inline" method="POST" action="{{ route('managerReport') }}" onsubmit="return confirm('<?= __('custom.send-mail-with-bank-balance-hours-to-the-person-in-charge') ?>')">
                                @csrf
                                <input type="hidden" name="action" value="sendMail" />
                                <button class="btn btn-outline-danger rounded-circle" title="@lang('custom.send-mail')">
                                    <i class="card-icon icofont-send-mail"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <th>@lang('custom.name')</th>
                            <th class="th-w-25">@lang('custom.balance')</th>
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
