@props(['url'])
<tr>
<td class="header">
{{-- <a href="{{ $url }}" style="display: inline-block;"> --}}
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{-- {{ $slot }} --}}
<img src="https://avatars.githubusercontent.com/u/24302454?v=4" class="logo" alt="JuanMX Logo" width="150" height="150">
@endif
</a>
</td>
</tr>
