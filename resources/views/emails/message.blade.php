Hi {{ $receiver->first_name }},<br>
You have received a message from {{ $sender->name }}, <a href="{{ $page_url }}">your {{ config('custom.code') }} page</a> visitor.<br><br>

- - - - -<br>
{{ $text }}<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;- {{ $sender->name }} @if($sender->email)&lsaquo;<a href="mailto:{{ $sender->email }}">{{ $sender->email }}</a>&rsaquo;@endif<br>
@if($sender->phone)&nbsp;&nbsp;&nbsp;&nbsp;- {{ $sender->phone }}<br>@endif<br>
- - - - -<br><br>

@if($sender->email)
    Feel free to reach {{ $sender->name }} via an email by hitting a "Reply" button.
@else
    Feel free to reach {{ $sender->name }} by {{ $sender->phone }}.<br>
@endif

<br><br>
Your <a href="{{ $page_url }}">{{ config('custom.code') }}</a> with &hearts;