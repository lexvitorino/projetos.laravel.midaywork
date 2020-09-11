@if(Session::has('message'))
    @if(!empty(Session::get('message')))
        <div role="alert" class="my-3 alert alert-{{ Session::get('messageType') }}">
            {{ Session::get('message') }}
        </div>
    @endif
@endif
