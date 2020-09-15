@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <ul>
            <h5><i class="icofont-ban mr-2"></i>Verifique os erros abaixo!</h5>
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('licenses.store') }}" method="POST">
        @csrf
        @if($user->is_admin)
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="user_id">Colaborador</label>
                    <select id="user_id" name="user_id" class="form-control mr-2" placeholder="Selecione o usuário...">
                        <option value="">Selecione o usuário</option>
                        @foreach($users as $user)
                        {{ $selected = $user->id === $selectedUserId ? 'selected' : '' }}
                        <option value='{{$user->id}}' {{$selected}}>{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="license_type">Tipo</label>
                <select id="license_type" name="license_type" class="form-control mr-2" placeholder="Selecione o tipo de licença...">
                    <option value="">Selecione um tipo</option>
                    <option value="vacation" {{ 'vacation' === old('license_type') ? 'selected' : '' }}>Férias</option>
                    <option value="others" {{ 'others' === old('license_type') ? 'selected' : '' }}>Outros</option>
                </select>
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        Abonado pela Empresa
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_bonus_date">Inicio</label>
                                <input type="date" id="start_bonus_date" name="start_bonus_date"
                                    class="form-control"
                                    value="{{ old('start_bonus_date') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_bonus_date">Fim</label>
                                <input type="date" id="end_bonus_date" name="end_bonus_date"
                                    class="form-control"
                                    value="{{ old('end_bonus_date') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        Descontado em Banco de Horas
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_discount_date">Inicio</label>
                                <input type="date" id="start_discount_date" name="start_discount_date"
                                    class="form-control"
                                    value="{{ old('start_discount_date') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date">Fim</label>
                                <input type="date" id="end_discount_date" name="end_discount_date"
                                    class="form-control"
                                    value="{{ old('end_discount_date') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
            <a href="{{route('licenses.index')}}"
                class="btn btn-secondary btn-lg">Cancelar</a>
        </div>
    </form>
</main>
@endsection
