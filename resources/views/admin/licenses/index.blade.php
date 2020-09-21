@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    <a class="btn btn-lg btn-primary mb-3" href="{{ route('licenses.create') }}">@lang('custom.new-license')</a>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th colspan="3"></th>
                <th colspan="2">@lang('custom.paid-by-the-company')</th>
                <th colspan="2">@lang('custom.discounted-in-bank-of-hours')</th>
                <th colspan="2"></th>
            </tr>
            <th>@lang('custom.name')</th>
            <th class="th-w-10">@lang('custom.type')</th>
            <th class="th-w-10">@lang('custom.status')</th>
            <th class="th-w-10">@lang('custom.start-date')</th>
            <th class="th-w-10">@lang('custom.end-date')</th>
            <th class="th-w-10">@lang('custom.start-date')</th>
            <th class="th-w-10">@lang('custom.end-date')</th>
            <th class="th-w-15" colspan="2">@lang('custom.action')</th>
        </thead>
        <tbody>
            @foreach ($licenses as $license)
            <tr>
                <td>{{ $license->user->name }}</td>
                <td>{{ $license->formatType($license->license_type) }}</td>
                <td>{{ $license->formatStatus($license->status) }}</td>
                <td>{{ $license->formatDateWithLocale($license->start_bonus_date) }}</td>
                <td>{{ $license->formatDateWithLocale($license->end_bonus_date) }}</td>
                <td>{{ $license->formatDateWithLocale($license->start_discount_date) }}</td>
                <td>{{ $license->formatDateWithLocale($license->end_discount_date) }}</td>
                <td>
                    @if($license->status === 'forecast' || $license->status === 'denied')
                    <a href="{{ route('licenses.edit',  ['license' => $license->id]) }}" title="@lang('custom.edit')"
                        class="btn btn-warning rounded-circle mr-2">
                        <i class="icofont-edit"></i>
                    </a>
                    @endif
                    @if($license->status === 'forecast' || $license->status === 'denied')
                    <form class="d-inline" method="POST" action="{{ route('licenses.destroy',  ['license' => $license->id]) }}" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger rounded-circle" title="@lang('custom.delete')">
                            <i class="icofont-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
                <td>
                    @if($user->is_admin && $license->status !== 'processed' && $license->status !== 'approved')
                    <form class="d-inline" method="POST" action="{{ route('licenses.update',  ['license' => $license->id]) }}" onsubmit="return confirm('Tem certeza que deseja aprovar?')">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="action" value="approved" />
                        <button class="btn btn-outline-success rounded-circle" title="@lang('custom.approved')">
                            <i class="icofont-check-alt"></i>
                        </button>
                    </form>
                    <form class="d-inline" method="POST" action="{{ route('licenses.update',  ['license' => $license->id]) }}" onsubmit="return confirm('Tem certeza que deseja reprovar?')">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="action" value="denied" />
                        <button class="btn btn-outline-danger rounded-circle" title="@lang('custom.denied')">
                            <i class="icofont-not-allowed"></i>
                        </button>
                    </form>
                    @endif
                    @if($user->is_admin && $license->status !== 'processed' && $license->status === 'approved')
                    <form class="d-inline" method="POST" action="{{ route('licenses.update',  ['license' => $license->id]) }}" onsubmit="return confirm('Tem certeza que deseja reprovar?')">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="action" value="processed" />
                        <button class="btn btn-outline-info rounded-circle" title="@lang('custom.processed')">
                            <i class="icofont-ui-next"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $licenses->links("admin.layouts.pagination") }}

</main>
@endsection
