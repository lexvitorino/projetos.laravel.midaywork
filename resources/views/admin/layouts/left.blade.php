<aside class="sidebar">
    <nav class="menu mt-3">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('dayRecord') }}">
                    <i class="icofont-ui-check mr-2"></i>
                    @lang('custom.titles.dayRecord')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('monthlyReport') }}">
                    <i class="icofont-ui-calendar mr-2"></i>
                    @lang('custom.titles.monthlyReport')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('licenses.index') }}">
                    <i class="icofont-history mr-2"></i>
                    @lang('custom.titles.license')
                </a>
            </li>
            @if($user->is_admin)
            <li class="nav-item">
                <a href="{{ route('managerReport') }}">
                    <i class="icofont-chart-histogram mr-2"></i>
                    @lang('custom.titles.managerReport')
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}">
                    <i class="icofont-users mr-2"></i>
                    @lang('custom.titles.users')
                </a>
            </li>
            @endif
        </ul>
    </nav>
    <div class="sidebar-widgets" id="sidebar-widgets">
        @include('admin.layouts.workResume')
    </div>
</aside>
