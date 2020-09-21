@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    <div>
        <form class="mb-4" action="{{ route('monthlyReport') }}" method="post">
            @csrf
            <div class="input-group">
                @if($user->is_admin)
                <select name="user" class="form-control mr-2">
                    <option value="">@lang('custom.select-the-user')</option>
                    @foreach($users as $u)
                    {{ $selected = $u->id === $selectedUserId ? 'selected' : '' }}
                    <option value='{{$u->id}}' {{$selected}}>{{$u->name}}</option>
                    @endforeach
                </select>
                @endif
                <select name="period" class="form-control">
                    @foreach($periods as $key => $month)
                    {{ $selected = $key === $selectedPeriod ? 'selected' : '' }}
                    <option value='{{$key}}' {{$selected}}>{{$month}}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary ml-2">
                    <i class="icofont-search"></i>
                </button>
            </div>
        </form>

        @if(!empty($totalBalance->balance))
        <div role="alert" class="my-3 alert alert-{{ $totalBalance->class }} text-right">
            @lang('custom.previous-balance') :: {{ $totalBalance->balance }}
        </div>
        @endif

        <table class="table table-bordered table-striped table-hover">

            <thead>
                <th>@lang('custom.day')</th>
                <th>@lang('custom.start') 1</th>
                <th>@lang('custom.end') 1</th>
                <th>@lang('custom.start') 2</th>
                <th>@lang('custom.end') 2</th>
                <th>@lang('custom.start') 3</th>
                <th>@lang('custom.end') 3</th>
                <th>@lang('custom.balance')</th>
            </thead>
            <tbody>
                @foreach($report as $registry)
                <tr>
                    <td>{{ $registry->formatDateWithLocale($registry->work_date) }}</td>
                    @if(empty($registry->status) || $registry->status === 'normal')
                    <td>{{ $registry->time1 }}</td>
                    <td>{{ $registry->time2 }}</td>
                    <td>{{ $registry->time3 }}</td>
                    <td>{{ $registry->time4 }}</td>
                    <td>{{ $registry->time5 }}</td>
                    <td>{{ $registry->time6 }}</td>
                    @endif
                    @if($registry->status === 'bonus-vocation')
                    <td colspan="6" class="bg-success"> Férias abonadas </td>
                    @endif
                    @if($registry->status === 'discounted-vocation')
                    <td colspan="6" class="bg-info"> Férias descontadas em banco de horas </td>
                    @endif
                    <td>{{ $registry->getBalance() }}</td>
                    @if($user->is_admin)
                    <td style="width: 10px">
                        <form class="d-inline" method="POST" action="{{ route('monthlyReport') }}" onsubmit="return confirm('<?= __('custom.recalculate-balance') ?>')">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="action" value="calcBalance" />
                            <input type="hidden" name="id" value="{{$registry->id}}" />
                            <button type="submit" class="btn btn-sm btn-link" title="@lang('custom.recalculate-balance')">
                                <i class="icofont-refresh"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
                <tr class="bg-primary text-white">
                    <td>@lang('custom.worked-hours')</td>
                    <td colspan="5">{{ $sumOfWorkedTime }}</td>
                    <td>Saldo Mensal</td>
                    <td>{{ $balance }}</td>
                    @if($user->is_admin)
                    <td style="width: 10px">
                        <form class="d-inline" method="POST" action="{{ route('monthlyReport') }}" onsubmit="return confirm('<?= __('custom.recalculate-every-day') ?>')">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="action" value="calcBalanceAll" />
                            <button type="submit" class="btn btn-sm btn-link" title="@lang('custom.recalculate-every-day')">
                                <i class="icofont-refresh" style="color: #fff"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</main>
@endsection
