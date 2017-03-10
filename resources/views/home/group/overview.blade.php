@extends('app')

@section('title', trans('group/overview.page_title'))

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
            'active' => 'overview'
        ])
    </div>
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ trans('group/overview.overview') }}</h3>

        @if (Session::has('success-message'))
        <div class="ui icon success message visible">
            {{ Session::get('success-message') }}
        </div>
        @endif

        <form class="ui form" method="post" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="two fields">
                <div class="required field">
                    <label>{{ trans('group/new.general_discipline') }}</label>
                    <div class="ui input">
                        <input name="creative_field1" type="text" placeholder="{{ trans('group/new.general_discipline_examples') }}" value="{{ $group->creative_field1 }}">
                    </div>
                </div>
                <div class="field">
                    <label>{{ trans('group/new.subdiscipline') }} ({{ trans('form.optional') }})</label>
                    <div class="ui input">
                        <input name="creative_field2" type="text" placeholder="{{ trans('group/new.subdiscipline_examples') }}" value="{{ $group->creative_field2 }}">
                    </div>
                </div>
            </div>
            <div class="required field">
                <label>
                    {{ trans('group/overview.class_title') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ trans('form.max_chars', ['chars' => 50]) }}</em>
                </label>
                <div class="ui input">
                    <input name="title" type="text" value="{{ $group->title }}" maxlength="50">
                </div>
                <small style="display: block; padding-top: 0.25rem; text-align: right;">{{ trans('group/overview.class_title_info') }}</small>
            </div><br />
            <div class="field">
                <label>
                    {{ trans('form.uvp') }} <i class="help circle icon link" data-content="{{ trans('form.uvp_info') }}" data-variation="large"></i>
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ trans('form.max_chars', ['chars' => 140]) }}</em>
                </label>
                <textarea name="uvp" style="height: 60px; min-height: 60px;" maxlength="140">{{ $group->uvp }}</textarea>
            </div>
            <div class="field">
                <label>{{ trans('group/overview.class_description') }}</label>
                <textarea name="description">{{ $group->description }}</textarea>
            </div>
            <div class="field">
                <label>
                    {{ trans('group/overview.for_whom') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ trans('form.max_chars', ['chars' => 255]) }}</em>
                </label>
                <textarea name="for_who" style="height: 60px; min-height: 60px;" maxlength="255">{{ $group->for_who }}</textarea>
            </div>

            <div class="ui submit red button">Save</div>
        </form>
    </div>
    <div id="tips-column" class="five wide column" style="background: #fff;">
        <div class="ui icon message" style="background: none; box-shadow: none;">
            <i class="asterisk icon yellow" style="display: inline-block; margin-right: 0;"></i>
            <div class="content">
                <div class="header">{{ trans('group/overview.helpers.overview') }}</div>
                <p>{!! trans('group/overview.helpers.overview_description') !!}</p>
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