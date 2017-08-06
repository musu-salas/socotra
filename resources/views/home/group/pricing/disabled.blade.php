@extends('app')

@section('title', __('Manage class pricing') . ' · ' . config('app.name'))

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
            'active' => 'pricing'
        ])
    </div>
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ __('Pricing') }}</h3>

        <div class="ui icon message">
            <i class="warning circle icon"></i>
            <div class="content">
                <p>{{ __('In our practice pricing is normally tied to the location of the class. Please add the location for your class to unlock editing of your pricing.') }}</p>
            </div>
        </div>

        <p>
            <a href="{{ url('home/classes', [$group->id, 'location']) }}" class="ui button red">{{ __('Add Location') }}</a>
        </p>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
@endsection