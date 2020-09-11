@if(isset($title))
<div class="content-title mb-4">
    @if($title->icon)
        <i class="icon {{ $title->icon }} mr-2"></i>
    @endif
    <div>
        <h1>{{ $title->title }}</h1>
        <h2>{{ $title->subtitle }}</h2>
    </div>
</div>
@endif
