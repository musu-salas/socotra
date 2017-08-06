@component('mail::message')
{{ __('Hi :name,', [ 'name' => $receiver->first_name ]) }},<br>
{{ __('You have received a message from :sender, [your :app page](:url) visitor.', [ 'sender' => $sender->name, 'app' => config('app.name'), 'url' => $page_url ]) }}

@component('mail::panel')
{{ $text }}

{{ $sender->name }}
@if($sender->email) &lt;[{{ $sender->email }}](mailto:{{ $sender->email }})&gt;@endif
<br>
@if($sender->phone){{ $sender->phone }}@endif
@endcomponent

@if($sender->email)
{{ __('Feel free to reach :sender via an email by hitting a "Reply" button.', [ 'name' => $sender->name ]) }}
@else
{{ __('Feel free to reach :sender by phone.', [ 'sender' => $sender->name ]) }}<br>
@endif

<br><br>
{!! __('Your [:app](:url) with &hearts;', [ 'app' => config('app.name'), 'url' => $page_url ]) !!}
@endcomponent
