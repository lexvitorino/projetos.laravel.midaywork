<div class="sidebar-widget">
    <i class="icon icofont-hour-glass text-primary"></i>
    <div class="info">
        <span class="main text-primary" {{ $activeClock === 'workedInterval' ? 'active-clock' : '' }}>
            {{ $workedInterval }}
        </span>
        <span class="label text-muted">@lang('custom.worked-hours')</span>
    </div>
</div>
<div class="division my-3"></div>
<div class="sidebar-widget">
    <i class="icon icofont-ui-alarm text-danger"></i>
    <div class="info">
        <span class="main text-danger" {{ $activeClock === 'exitTime' ? 'active-clock' : '' }}>
            {{ $exitTime }}
        </span>
        <span class="label text-muted">@lang('custom.exit-time')</span>
    </div>
</div>
