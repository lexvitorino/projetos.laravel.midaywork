@extends('auth.layouts.app')

@section('title', 'Login')

@section('content')

<form class="form-login" action="{{ url('/painel/login') }}" method="post">
    @csrf

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <ul>
            <h5><i class="icofont-ban mr-2"></i>@lang('validation.custom.check-errors-below')</h5>
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif

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
                <label for="email">@lang('auth.email')</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="{{ __('custom.information.information-filed', ['attribute' => __('auth.email') ]) }}" autofocus>
            </div>
            <div class="form-group">
                <label for="password">@lang('auth.password')</label>
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="{{ __('custom.information.information-filed', ['attribute' => __('auth.password') ]) }}">
                <div class="invalid-feedback">
                </div>
            </div>
            <div class="form-group">
                <label for="remember" class="form-check-label">@lang('auth.remember-me')</label>
                <input type="checkbox" id="remember" name="remember"
                    class="form-check-input ml-2"
                    {{ ($user->remember ?? false) ? 'checked' : '' }}>
                <div class="invalid-feedback">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-lg btn-primary">@lang('auth.login')</button>
            <a href="{{ url('/painel/register') }}" class="btn btn-link mt-1">@lang('auth.register-new-member')</a>
        </div>
    </div>
</form>
@endsection
