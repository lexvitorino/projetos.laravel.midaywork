@extends('auth.layouts.app')

@section('title', 'Register')

@section('content')

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

<form class="form-login" action="{{ url('/painel/register') }}" method="post">
    @csrf
    <div class="login-card card">
        <div class="card-header">
            <i class="icofont-travelling mr-2"></i>
            <span class="font-weight-light">MI </span>
            <span class="font-weight-bold mx-2">Day</span>
            <span class="font-weight-light">Work</span>
            <i class="icofont-runner-alt-1 ml-2"></i>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Como deseja ser chamado?" autofocus
                    value="{{old('name')}}" />
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Informe o e-mail"
                    value="{{old('email')}}" />
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Informe a senha" />
            </div>
            <div class="form-group">
                <label for="password-confirm">Confirme Password</label>
                <input type="password" id="password-confirm" name="password_confirmation"
                    class="form-contro l@error('password') is-invalid @enderror"
                    placeholder="Confirme Password"/>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-lg btn-primary">Registrar</button>
            <a href="{{ url('/painel/login') }}" class="btn btn-link mt-1">Já sou um membro</a>
        </div>
    </div>
</form>
@endsection
