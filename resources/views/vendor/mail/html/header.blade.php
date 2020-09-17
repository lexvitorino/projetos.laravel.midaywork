<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'MIDayWork')
<img src="{{ url('img/logo.png') }}" class="logo" alt="{{ $slot }}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
