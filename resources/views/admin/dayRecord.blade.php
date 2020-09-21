@extends('admin.layouts.app')

@section('content')
<main class="content">

    @include('admin.layouts.title')
    @include('admin.layouts.message')

    <div class="card">
        <div class="card-header">
            <h3>{{ $today }}</h3>
            <p class="mb-0">@lang('custom.titles.keep-your-point-consistent')</p>
        </div>
        <div class="card-body">
            <div class="d-flex m-5 justify-content-around">
                <span class="record">@lang('custom.start') 1: <b id="time1">{{ $workingHours->time1 ?? '--:--:--' }}</b></span>
                <span class="record">@lang('custom.end') 1: <b id="time2">{{ $workingHours->time2 ?? '--:--:--' }}</b></span>
            </div>
            <div class="d-flex m-5 justify-content-around">
                <span class="record">@lang('custom.start') 2: <b id="time3">{{ $workingHours->time3 ?? '--:--:--' }}</b></span>
                <span class="record">@lang('custom.end') 2: <b id="time4">{{ $workingHours->time4 ?? '--:--:--' }}</b></span>
            </div>
            <div class="d-flex m-5 justify-content-around">
                <span class="record">@lang('custom.start') 3: <b id="time5">{{ $workingHours->time5 ?? '--:--:--' }}</b></span>
                <span class="record">@lang('custom.end') 3: <b id="time6">{{ $workingHours->time6 ?? '--:--:--' }}</b></span>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-center">
            <form class="d-inline" method="POST" action="{{ route('dayRecord.save') }}">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="icofont-check mr-1"></i>
                    @lang('custom.hit-the-spot')
                </button>
            </form>
        </div>
    </div>

    <form class="mt-5" action="{{ route('dayRecord') }}" method="post">
        @csrf
        <div class="input-group no-border">
            <input type="date" name="forcedDate" class="form-control mr-3" />
            <input type="text" name="forcedTime" class="form-control time"
                placeholder="@lang('custom.enter-the-time-to-manually-point')">
            <button type="submit" class="btn btn-danger ml-3">
                @lang('custom.simulate-point')
            </button>
        </div>
    </form>

</main>
@endsection

@section('scripts')
<script>
    window.workResume = "{{ route('dayRecord.workResume') }}";
</script>
<script src="{{ url('js/plugins/jquery.mask/jquery.mask.min.js') }}"></script>
<script src="{{ url('js/views/dayRecord/index.js') }}"></script>
@endsection
