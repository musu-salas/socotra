Hi {{ $receiver->first_name }},<br>
<b>{{ $sender->name }}</b>, <a href="{{ $page_url }}">your {{ config('custom.code') }} page</a> visitor, <b>has a willing to join the class<i>!</i></b><br><br>

Contact information provided:<br>
@if($sender->email)
    &nbsp;&nbsp;&nbsp;&nbsp;- {{ $sender->name }} &lsaquo;<a href="mailto:{{ $sender->email }}">{{ $sender->email }}</a>&rsaquo;<br>
@endif

@if($sender->phone)
    &nbsp;&nbsp;&nbsp;&nbsp;- {{ $sender->phone }}<br>
@endif

<br>
@if($sender->email)
    Feel free to reach {{ $sender->name }} via an email by hitting a "Reply" button.<br>
    <em>Please include more details regarding how to begin attending your class.</em><br>
@else
    Feel free to reach {{ $sender->name }} by {{ $sender->phone }}.<br>
@endif

<br><br>
Your <a href="{{ $page_url }}">{{ config('custom.code') }}</a> with &hearts;