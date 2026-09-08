@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<h1 style="font-size: 20px; font-weight: bold; color: #3d4852;">{{ $slot }}</h1>
</a>
</td>
</tr>
