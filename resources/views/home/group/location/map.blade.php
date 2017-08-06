@extends('app')

@section('title', __('Map class location') . ' · ' . config('app.name'))

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
            'active' => 'location'
        ])
    </div>
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ __('Review your location on the map') }}</h3>
        <p><i class="icon marker"></i> {{ $location->full_address }}, {{ $location->country->name }}</p>

        <form class="ui form" action="" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="latlng" value="{{ $location->latlng }}">

            <div id="message" class="ui icon hidden warning message">
                <i class="marker icon"></i>
                <div class="content">
                    <div class="header"></div>
                    <p></p>
                </div>
            </div>

            <div id="map" data-location="{{ json_encode($location) }}"></div><br />

            <button class="ui red button" type="submit">{{ __('Save') }}</button>
        </form>
    </div>

    <div id="tips-column" class="five wide column" style="background: #fff;">
        <div class="ui icon message" style="background: none; box-shadow: none;">
            <i class="asterisk icon yellow" style="display: inline-block; margin-right: 0;"></i>
            <div class="content">
                <div class="header">{{ __('Map location') }}</div>
                <p>{{ __('Drag red marker to the exact location of your class.') }}</p>
                <p>{{ __('You can also switch to "Satellite" view and/or zoom in to see roofs for better identification of your building.') }}</p>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="group_id" value="{{ $group->id }}" />
@endsection

@section('scripts')
{!! HTML::script( 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . env('GOOGLE_MAPS_KEY') ) !!}
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.location.map.js') ) !!}
@endsection