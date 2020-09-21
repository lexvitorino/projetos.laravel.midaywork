
    <footer class="footer">
        <span>@lang('auth.developed-with')</span>
        <span><i class="icofont-heart text-danger mx-1"></i></span>
        <span>@lang('auth.by') MI<span class="text-danger">7</span>Dev</span>
    </footer>
    <script src="{{ url(mix('js/jquery.js')) }}"></script>
    <script src="{{ url(mix('js/bootstrap.js')) }}"></script>
    <script src="{{ url(mix('js/jquery-mask.js')) }}"></script>
    <script src="{{ url(mix('js/sweetalert.js')) }}"></script>
    <script src="{{ url(mix('js/jquery-blockui.js')) }}"></script>
    <script src="{{ url(mix('admin/js/scripts.js')) }}"></script>
    @yield('scripts')
</body>
</html>
