@extends('app')

@section('title', trans('group/pricing.page_titles.list'))

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
            'active' => 'pricing'
        ])
    </div>
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ trans('group/pricing.pricing') }}</h3>

        @foreach ($locations as $location)
        <table class="ui striped compact table">
            <thead>
                <tr>
                    <th colspan="2">{{ $location->address_line_1 }}, {{ $location->zip }}, {{ $location->city }}</th>
                    <th class="right aligned collapsing">{{-- TODO: Display disctrict --}}</th>
                </tr>
            </thead>

            <tbody>
                @foreach (array_values(array_sort($location->pricings, function($p) { return $p->pivot->price; })) as $variation)
                <tr>
                    <td>{{ $variation->title }}</td>
                    <td class="collapsing"><var class="currency-symbol">{{ $location->currency->symbol }}</var> <span>{{ $variation->pivot->price }}</span></td>
                    <td class="right aligned collapsing">
                        <a href="{{ url('/home/classes', [$group->id, 'pricing', $variation->id]) }}" title="">
                            <i class="icon write"></i>
                        </a>
                    </td>
                </tr>
                @endforeach

                <tr>
                    <td colspan="3">
                        <a href="{{ url('/home/classes', [$group->id, 'pricing', 'new']) }}" title="">
                            <i class="icon plus"></i> {{ trans('group/pricing.add_pricing') }}
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="ui divider"></div><br />
        @endforeach

    </div>
    <div id="tips-column" class="five wide column" style="background: #fff;">
        <div class="ui icon message" style="background: none; box-shadow: none;">
            <i class="asterisk icon yellow" style="display: inline-block; margin-right: 0;"></i>
            <div class="content">
                <div class="header">{{ trans('group/pricing.helpers.manage_pricing') }}</div>
                <p>{!! trans('group/pricing.helpers.manage_pricing_description') !!}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
@endsection