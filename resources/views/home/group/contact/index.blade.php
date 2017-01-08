@extends('app')

@section('title', trans('group/contact.page_title'))

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
            'active' => 'contact'
        ])
    </div>
    <div id="prices-column" class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ trans('group/contact.contact_information') }}</h3>

        @if (!$group->phone)
            <p>{{ trans('group/contact.phone_info') }}</p>
        @endif

        @if (Session::has('success-message'))
        <div class="ui icon success message visible">
            {{ Session::get('success-message') }}
        </div>
        @endif

        <form class="ui form" method="post" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="field">
                <label>{{ trans('form.phone_number') }}</label>
                <div class="ui input">
                    <input name="phone" type="text" placeholder="{{ trans('form.phone_number_sample') }}" value="{{ $group->phone }}">
                </div>
            </div>

            <div class="field">
                <label>{{ trans('form.email_address') }} <a href="{{ url(sprintf('home/settings?followTo=/home/classes/%d/contact&focus=email', $group->id)) }}" style="margin-left: 1rem;"><i class="write icon"></i>{{ trans('buttons.edit') }}</a></label>
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