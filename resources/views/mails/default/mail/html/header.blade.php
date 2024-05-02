@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ url('img/logo.png') }}" class="logo" alt="Taller 1100"> 
{{ $slot }}
</a>
</td>
</tr>
