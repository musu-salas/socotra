@component('mail::message')
{{ __('Hi :name,', [ 'name' => $receiver->first_name ]) }}<br>
{!! __('**:sender**, [your :app page](:url) visitor, **has a willing to join the class_!_**', [ 'sender' => $sender->name, 'app' => config('app.name'), 'url' => $page_url ]) !!}

@component('mail::panel')
Contact information provided:
>{{ $sender->name }}
@if($sender->email) &lt;[{{ $sender->email }}](mailto:{{ $sender->email }})&gt;@endif
<br>
@if($sender->phone){{ $sender->phone }}@endif
@endcomponent

@if($sender->email)
{{ __('Feel free to reach :sender via an email by hitting a "Reply" button.', [ 'sender' => $sender->name ]) }}

{{ __('_Please include more details regarding how to begin attending your class._') }}
@else
{{ __('Feel free to reach :sender by phone.', [ 'sender' => $sender->name ]) }}
@endif

<br><br>
{!! __('Your [:app](:url) with &hearts;', [ 'app' => config('app.name'), 'url' => $page_url ]) !!}
@endcomponent