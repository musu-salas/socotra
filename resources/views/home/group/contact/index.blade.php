@extends('app')

@section('title', __('Class contact information') . ' · ' . config('app.name'))

@section('content')

@include('topbar', [
    'user' => $user,
    'activePage' => 'classes'
])

@include('home.group.statusbar', [
    'group' => $group,
    'menu' => $menu
])

<div class="ui stackable doubling grid" style="padding-top: 80px; margin: 0;">
    <div id="steps-column" class="three wide column" style="background: #eee;">
        @include('home.group.menu', [
            'group' => $group,
            'menu' => $menu,
            'active' => 'contact'
        ])
    </div>
    <div id="prices-column" class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ __('Contact information') }}</h3>

        @if (!$group->phone)
            <p>{{ __('Please add your phone number, so people interested in joining the class are able to reach you out.') }}</p>
        @endif

        @if (Session::has('success-message'))
        <div class="ui icon success message visible">
            {{ Session::get('success-message') }}
        </div>
        @endif

        <form class="ui form" method="post" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="field">
                <label>{{ __('Phone number') }}</label>
                <div class="ui input">
                    <input name="phone" type="text" placeholder="{{ __('Phone number with a country code (e.g. +1 123-456-7890)') }}" value="{{ $group->phone }}">
                </div>
            </div>

            <div class="field">
                <label>{{ __('Email address') }} <a href="{{ url(sprintf('home/settings?followTo=/home/classes/%d/contact&focus=email', $group->id)) }}" style="margin-left: 1rem;"><i class="write icon"></i>{{ __('Edit') }}</a></label>
                <div class="ui input">
                    <input readonly="readonly" disabled="disabled" type="text" value="{{ $group->owner->email }}">
                </div>
            </div>

            <div class="ui submit red button">Save</div>
        </form>
    </div>
    <div id="tips-column" class="five wide column" style="background: #fff;"></div>
</div>
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.contact.js') ) !!}
@endsection