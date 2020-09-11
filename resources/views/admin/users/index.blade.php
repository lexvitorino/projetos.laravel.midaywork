@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    <a class="btn btn-lg btn-primary mb-3" href="{{ route('users.create') }}">Novo Usuário</a>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <th>Nome</th>
            <th>Email</th>
            <th>Data de Admissão</th>
            <th>Data de Desligamento</th>
            <th>Ações</th>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->start_date }}</td>
                <td>{{ $user->end_date }}</td>
                <td>
                    <a href="{{ route('users.edit',  ['user' => $user->id]) }}"
                        class="btn btn-warning rounded-circle mr-2">
                        <i class="icofont-edit"></i>
                    </a>
                    @if($userId != $user->id)
                    <form class="d-inline" method="POST" action="{{ route('users.destroy',  ['user' => $user->id]) }}" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger rounded-circle">
                            <i class="icofont-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links("admin.users.pagination") }}

</main>
@endsection
