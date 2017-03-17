@extends('app')

@section('title', trans('group/location.page_titles.list'))

@section('content')
<div class="ui fixed borderless menu" style="box-shadow: 0 0 1px rgba(39, 41, 43, 0.15);">
    <div class="ui page stackable doubling grid" style="margin: 0;">
        <a class="item" href="{{ url('/') }}" title="{{ config('app.name') }}">
            <strong>{{ config('app.name') }}</strong>
        </a>

        @include('home.navigation', [
            'user' => $user,
            'active' => 'classes'
        ])
    </div>
</div>

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
    <div class="thirteen wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ trans('group/location.locations') }}</h3>

        <table class="ui striped compact table">
            <tbody>

                @foreach ($locations as $location)
                <tr>
                    <td>{{ $location->address_line_1 }}</td>
                    <td class="collapsing">{{ $location->zip }}</td>
                    <td class="collapsing">{{ $location->city }}</td>
                    <td class="right aligned collapsing">
                        <a href="{{ url('/home/classes/' . $group->id . '/location/' . $location->id . '/map') }}" title="" @if(!$location->latlng)data-content="{{ trans('group/location.map_notset_info') }}" data-position="top right"@endif>
                            <i class="icon {{ $location->latlng ? 'black' : 'red' }} marker"></i>
                        </a>
                    </td>
                    <td class="right aligned collapsing">
                        <a href="{{ url('/home/classes', [$group->id, 'location', $location->id]) }}" title="">
                            <i class="icon write"></i>
                        </a>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <a href="{{ url('/home/classes', [$group->id, 'location', 'new']) }}" class="ui button red">{{ trans('group/location.add_address') }}</a>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
@endsection