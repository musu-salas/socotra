@extends('app')

@section('title', trans('group/pricing.page_titles.list'))

@section('content')
<div class="ui fixed borderless menu" style="box-shadow: 0 0 1px rgba(39, 41, 43, 0.15);">
    <div class="ui page stackable doubling grid" style="margin: 0;">
        <a class="item" href="{{ url('/') }}" title="{{ config('custom.code') }}">
            <strong>{{ config('custom.code') }}</strong>
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
            'active' => 'pricing'
        ])
    </div>
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ trans('group/pricing.pricing') }}</h3>

        <div class="ui icon message">
            <i class="warning circle icon"></i>
            <div class="content">
                <p>{{ trans('group/pricing.pricing_disabled') }}</p>
            </div>
        </div>

        <p>
            <a href="{{ url('/home/classes', [$group->id, 'location']) }}" class="ui button red">{{ trans('group/pricing.add_location') }}</a>
        </p>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
@endsection