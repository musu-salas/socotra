@component('mail::message')
Hi {{ $receiver->first_name }},<br>
**{{ $sender->name }}**, [your {{ config('app.name') }} page]({{ $page_url }}) visitor, **has a willing to join the class_!_**

@component('mail::panel')
Contact information provided:
>{{ $sender->name }}
@if($sender->email) &lt;[{{ $sender->email }}](mailto:{{ $sender->email }})&gt;@endif
<br>
@if($sender->phone){{ $sender->phone }}@endif
@endcomponent

@if($sender->email)
Feel free to reach {{ $sender->name }} via an email by hitting a "Reply" button.

_Please include more details regarding how to begin attending your class._
@else
Feel free to reach {{ $sender->name }} by phone.
@endif

<br><br>
Your [{{ config('app.name') }}]({{ $page_url }}) with &hearts;
@endcomponent