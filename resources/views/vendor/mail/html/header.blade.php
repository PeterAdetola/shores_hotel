@props(['url'])

<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
{{--            <img src="{{ url('/email/logo.png') }}" width="131" height="62">--}}
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo-email.png'))) }}" width="60" height="72">

</a>
</td>
</tr>
