@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    <a class="btn btn-lg btn-primary mb-3" href="{{ route('users.create') }}">@lang('custom.new-user')</a>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <th>@lang('custom.name')</th>
            <th class="th-w-25">@lang('custom.email')</th>
            <th class="th-w-15">@lang('custom.admission-date')</th>
            <th class="th-w-15">@lang('custom.termination-date')</th>
            <th class="th-w-10">@lang('custom.action')</th>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->formatDateWithLocale($user->start_date) }}</td>
                <td>{{ $user->formatDateWithLocale($user->end_date) }}</td>
                <td>
                    <a href="{{ route('users.edit',  ['user' => $user->id]) }}" title="@lang('custom.edit')"
                        class="btn btn-warning rounded-circle mr-2">
                        <i class="icofont-edit"></i>
                    </a>
                    @if($userId != $user->id)
                    <form class="d-inline" method="POST" action="{{ route('users.destroy',  ['user' => $user->id]) }}" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger rounded-circle" title="@lang('custom.delete')">
                            <i class="icofont-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links("admin.layouts.pagination") }}

</main>
@endsection
