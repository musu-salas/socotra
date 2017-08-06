@extends('app')

@section('title', __('Class overview') . ' · ' . config('app.name'))

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
            'active' => 'overview'
        ])
    </div>
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ __('Class overview') }}</h3>

        @if (Session::has('success-message'))
        <div class="ui icon success message visible">
            {{ Session::get('success-message') }}
        </div>
        @endif

        <form class="ui form" method="post" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="two fields">
                <div class="required field">
                    <label>{{ __('General discipline') }}</label>
                    <div class="ui input">
                        <input name="creative_field1" type="text" placeholder="{{ __('e.g. dancing, piano, fitness, math, design...') }}" value="{{ $group->creative_field1 }}">
                    </div>
                </div>
                <div class="field">
                    <label>{{ __('Subdiscipline') }} ({{ __('optional') }})</label>
                    <div class="ui input">
                        <input name="creative_field2" type="text" placeholder="{{ __('Salsa for dancing, interior design for design...') }}" value="{{ $group->creative_field2 }}">
                    </div>
                </div>
            </div>
            <div class="required field">
                <label>
                    {{ __('Class title') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ __('Max :chars chars', ['chars' => 50]) }}</em>
                </label>
                <div class="ui input">
                    <input name="title" type="text" value="{{ $group->title }}" maxlength="50">
                </div>
                <small style="display: block; padding-top: 0.25rem; text-align: right;">{{ __('Google doesn\'t like words all written with capital letters, please use only in abbreviations.') }}</small>
            </div><br />
            <div class="field">
                <label>
                    {{ __('Class unique proposition') }} <i class="help circle icon link" data-content="{{ __('A clear statement that describes the benefit of your class and and what distinguishes it from others.') }}" data-variation="large"></i>
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ __('Max :chars chars', ['chars' => 140]) }}</em>
                </label>
                <textarea name="uvp" style="height: 60px; min-height: 60px;" maxlength="140">{{ $group->uvp }}</textarea>
            </div>
            <div class="field">
                <label>{{ __('Class detailed description') }}</label>
                <textarea name="description">{{ $group->description }}</textarea>
            </div>
            <div class="field">
                <label>
                    {{ __('For whom this class would suit perfectly?') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ __('Max :chars chars', ['chars' => 255]) }}</em>
                </label>
                <textarea name="for_who" style="height: 60px; min-height: 60px;" maxlength="255">{{ $group->for_who }}</textarea>
            </div>

            <div class="ui submit red button">{{ __('Save') }}</div>
        </form>
    </div>
    <div id="tips-column" class="five wide column" style="background: #fff;">
        <div class="ui icon message" style="background: none; box-shadow: none;">
            <i class="asterisk icon yellow" style="display: inline-block; margin-right: 0;"></i>
            <div class="content">
                <div class="header">{{ __('Filling in class overview') }}</div>
                <p>{!! __('Try to describe your class clearly: set class title, unique proposition, detailed description and perfect audience. <br /><br />"Unique proposition" is a clear statement that describes the benefit of your class and and what distinguishes it from others.') !!}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.overview.js') ) !!}
@endsection