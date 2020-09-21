@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

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

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">@lang('custom.name')</label>
                <input type="text" id="name" name="name" placeholder="{{ __('custom.information.information-filed', ['attribute' => __('custom.name') ]) }}"
                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            </div>
            <div class="form-group col-md-6">
                <label for="email">@lang('custom.email')</label>
                <input type="email" id="email" name="email" placeholder="{{ __('custom.information.information-filed', ['attribute' => __('custom.email') ]) }}"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="password">@lang('custom.new-password')</label>
                <input type="password" id="password" name="password" placeholder="{{ __('custom.information.information-filed', ['attribute' => __('custom.new-password') ]) }}"
                    class="form-control @error('password') is-invalid @enderror" />
            </div>
            <div class="form-group col-md-6">
                <label for="password_confirmation">@lang('custom.confirm-password')</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ __('custom.information.information-filed', ['attribute' => __('custom.confirm-password') ]) }}"
                    class="form-control @error('password') is-invalid @enderror" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="start_date">@lang('custom.admission-date')</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date') }}" />
            </div>
            <div class="form-group col-md-6">
                <label for="end_date">@lang('custom.termination-date')</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date') }}" />
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <label for="signal">@lang('custom.initial-balance')</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <select type="text" id="signal" name="signal" class="form-control">
                            <option value="+" {{ '+' === old('signal') ? 'selected' : '' }}>+</option>
                            <option value="-" {{ '-' === old('signal') ? 'selected' : '' }}>-</option>
                        </select>
                    </div>
                    <input type="text" id="time_balance" name="time_balance" placeholder="{{ __('custom.information.information-filed', ['attribute' => __('custom.initial-balance') ]) }}"
                        class="form-control" value="{{ old('time_balance') }}">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group form-check col-md-6">
                <label for="is_admin" class="form-check-label">@lang('custom.administrator')?</label>
                <input type="checkbox" id="is_admin" name="is_admin" class="form-check-input ml-2" {{ old('is_admin') ? 'checked' : '' }} />
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-primary btn-lg">@lang('custom.register')</button>
            <a href="{{route('users.index')}}" class="btn btn-secondary btn-lg">@lang('custom.cancel')</a>
        </div>
    </form>
</main>
@endsection
