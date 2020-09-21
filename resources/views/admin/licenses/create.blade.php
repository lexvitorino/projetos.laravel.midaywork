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

    <form action="{{ route('licenses.store') }}" method="POST">
        @csrf
        @if($user->is_admin)
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="user_id">@lang('custom.collaborator')</label>
                    <select id="user_id" name="user_id" class="form-control mr-2">
                        <option value="">{{ __('custom.information.information-filed', ['attribute' => __('custom.collaborator') ]) }}</option>
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
                <label for="license_type">@lang('custom.type')</label>
                <select id="license_type" name="license_type" class="form-control mr-2">
                    <option value="">{{ __('custom.information.information-filed', ['attribute' => __('custom.type') ]) }}</option>
                    <option value="vacation" {{ 'vacation' === old('license_type') ? 'selected' : '' }}>@lang('custom.vacation')</option>
                    <option value="others" {{ 'others' === old('license_type') ? 'selected' : '' }}>@lang('custom.others')</option>
                </select>
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        @lang('custom.paid-by-the-company')
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_bonus_date">@lang('custom.start')</label>
                                <input type="date" id="start_bonus_date" name="start_bonus_date"
                                    class="form-control"
                                    value="{{ old('start_bonus_date') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_bonus_date">@lang('custom.end')</label>
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
                        @lang('custom.discounted-in-bank-of-hours')
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_discount_date">@lang('custom.start')</label>
                                <input type="date" id="start_discount_date" name="start_discount_date"
                                    class="form-control"
                                    value="{{ old('start_discount_date') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date">@lang('custom.end')</label>
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
            <button type="submit" class="btn btn-primary btn-lg">@lang('custom.register')</button>
            <a href="{{route('licenses.index')}}"
                class="btn btn-secondary btn-lg">@lang('custom.cancel')</a>
        </div>
    </form>
</main>
@endsection
