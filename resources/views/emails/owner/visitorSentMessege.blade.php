@component('mail::message')
Hi {{ $receiver->first_name }},<br>
You have received a message from {{ $sender->name }}, [your {{ config('app.name') }} page]({{ $page_url }}) visitor.

@component('mail::panel')
{{ $text }}

{{ $sender->name }}
@if($sender->email) &lt;[{{ $sender->email }}](mailto:{{ $sender->email }})&gt;@endif
<br>
@if($sender->phone){{ $sender->phone }}@endif
@endcomponent

@if($sender->email)
Feel free to reach {{ $sender->name }} via an email by hitting a "Reply" button.
@else
Feel free to reach {{ $sender->name }} by phone.<br>
@endif

<br><br>
Your [{{ config('app.name') }}]({{ $page_url }}) with &hearts;
@endcomponent
