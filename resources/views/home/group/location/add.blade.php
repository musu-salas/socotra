@extends('app')

@section('title', trans('group/location.page_titles.' . ($location ? 'edit' : 'add')))

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
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">{{ trans('group/location.' . ($location ? 'edit' : 'add') . '_location') }}</h3>

        <form class="ui form" action="" method="post" autocomplete="off">
            <input autocomplete="false" name="hidden" type="text" style="display: none;" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="ui error message {{ $errors->count() ? 'visible' : '' }}">
                <div class="content">
                    <ul class="list">
                        @if ($errors->count())
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="required nine wide field">
                <label>{{ trans('form.country') }}</label>
                <div class="ui selection dropdown">
                    <input name="country_id" type="hidden" value="{{ $location ? $location->country_id : '' }}">
                    <div class="text">{{ $location && $location->country_id ? $location->country->name : trans('form.select_country') }}</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        @foreach ($countries as $country)
                        <div class="item {{ $location && $location->country_id === $country->id ? 'active' : '' }}" data-value="{{ $country->id }}">{{ $country->name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="required two wide field">
                <label>{{ trans('form.currency') }}</label>
                <div class="ui selection dropdown">
                    <input name="currency_id" type="hidden" value="{{ $location ? $location->currency->id : '' }}">
                    <div class="text">{{ $location ? $location->currency->code : trans('form.select_currency') }}</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        @foreach ($currencies as $currency)
                        <div class="item {{ $location && $location->currency_id === $currency->id ? 'active' : '' }}" data-value="{{ $currency->id }}">{{ $currency->code }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="required field">
                <label>{{ trans('form.address_line_1') }}</label>
                <div class="ui input">
                    <input name="address_line_1" value="{{ $location ? $location->address_line_1 : '' }}" type="text" placeholder="{{ trans('form.address_line_1_sample') }}" maxlength="255">
                </div>
            </div>
            <div class="field">
                <label>{{ trans('form.address_line_2') }}</label>
                <div class="ui input">
                    <input name="address_line_2" value="{{ $location ? $location->address_line_2 : '' }}" type="text" maxlength="255">
                </div>
            </div>
            <div class="two fields">
                <div class="required field">
                    <label>{{ trans('form.city') }}</label>
                    <div class="ui input">
                        <input name="city" value="{{ $location ? $location->city : '' }}" type="text" maxlength="255">
                    </div>
                </div>
                <div class="field">
                    <label>{{ trans('form.state_province_region') }}</label>
                    <div class="ui input">
                        <input name="state" value="{{ $location ? $location->state : '' }}" type="text" maxlength="255">
                    </div>
                </div>
            </div>
            <div class="two fields">
                <div class="required field">
                    <label>{{ trans('form.zip') }}</label>
                    <div class="ui input">
                        <input name="zip" type="text" value="{{ $location ? $location->zip : '' }}" maxlength="15">
                    </div>
                </div>
                <div class="field">
                    <label>{{ trans('form.district') }}</label>
                    <div class="ui input">
                        <input name="district" type="text" value="{{ $location ? $location->district : '' }}">
                    </div>
                    <small style="display: block; padding-top: 0.25rem; text-align: right;">{{ trans('form.district_info') }}</small>
                </div>
            </div><br />
            <div class="field">
                <label>
                    {{ trans('group/location.how_to_find') }} <i class="help circle icon link" data-content="{{ trans('group/location.how_to_find_info') }}" data-variation="wide"></i>
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ trans('form.max_chars', ['chars' => 255]) }}</em>
                </label>
                <textarea name="how_to_find" style="height: 60px; min-height: 60px;" maxlength="255">{{ $location ? $location->how_to_find : '' }}</textarea>
            </div>

            <div class="ui stackable doubling grid">
                <div class="eight wide column">
                    <button class="ui red right labeled icon button" type="submit">
                        <i class="marker icon"></i>
                        {{ trans('buttons.' . ($location ? 'update' : 'save')) }} &amp; {{ trans('group/location.map') }}
                    </button>
                </div>
                <div class="eight wide column right aligned">
                    @if ($location)
                    <p style="padding-top: 0.78571em;">
                        <a
                            id="delete"
                            href="{{ url('/api/v1/classes', [$group->id, 'location', $location->id]) }}"
                            title=""
                            data-deleting-label="{{ trans('group/location.deleting') }}"
                            data-error-label="{{ trans('group/location.problem_deleting') }}"
                        >{{ trans('group/location.delete') }}</a>
                    </p>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div id="tips-column" class="five wide column" style="background: #fff;">
        <div class="ui icon message" style="background: none; box-shadow: none;">
            <i class="asterisk icon yellow" style="display: inline-block; margin-right: 0;"></i>
            <div class="content">
                <div class="header">{{ trans('group/location.helpers.edit_location') }}</div>
                <p>{!! trans('group/location.helpers.edit_location_description') !!}</p>
            </div>
        </div>
    </div>
</div>

<div id="delete-modal" class="ui small modal">
    <div class="header">{{ trans('group/location.really_delete') }}</div>
    <div class="content">
        <p>{{ trans('group/location.really_delete_info') }}</p>
    </div>
    <div class="actions">
        <div class="ui basic cancel button">{{ trans('buttons.cancel') }}</div>
        <div class="ui red right labeled icon ok button">
            {{ trans('buttons.delete') }}
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>

<input type="hidden" name="group_id" value="{{ $group->id }}" />
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.location.js') ) !!}
@endsection