@extends('app')

@if($location)
    @section('title', __('Edit class location') . ' · ' . config('app.name'))
@else
    @section('title', __('Add class location') . ' · ' . config('app.name'))
@endif

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
        <h3 class="ui header">{{ $location ? __('Edit class location') : __('Add class location') }}</h3>

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
                <label>{{ __('Country') }}</label>
                <div class="ui selection dropdown">
                    <input name="country_id" type="hidden" value="{{ $location ? $location->country_id : '' }}">
                    <div class="text">{{ $location && $location->country_id ? $location->country->name : __('Select country') }}</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        @foreach ($countries as $country)
                        <div class="item {{ $location && $location->country_id === $country->id ? 'active' : '' }}" data-value="{{ $country->id }}">{{ $country->name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="required two wide field">
                <label>{{ __('Currency') }}</label>
                <div class="ui selection dropdown">
                    <input name="currency_id" type="hidden" value="{{ $location ? $location->currency->id : '' }}">
                    <div class="text">{{ $location ? $location->currency->code : __('Select currency') }}</div>
                    <i class="dropdown icon"></i>
                    <div class="menu">
                        @foreach ($currencies as $currency)
                        <div class="item {{ $location && $location->currency_id === $currency->id ? 'active' : '' }}" data-value="{{ $currency->id }}">{{ $currency->code }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="required field">
                <label>{{ __('Address line 1') }}</label>
                <div class="ui input">
                    <input name="address_line_1" value="{{ $location ? $location->address_line_1 : '' }}" type="text" placeholder="{{ __('House name/number + street/road') }}" maxlength="255">
                </div>
            </div>
            <div class="field">
                <label>{{ __('Address line 2') }}</label>
                <div class="ui input">
                    <input name="address_line_2" value="{{ $location ? $location->address_line_2 : '' }}" type="text" maxlength="255">
                </div>
            </div>
            <div class="two fields">
                <div class="required field">
                    <label>{{ __('City') }}</label>
                    <div class="ui input">
                        <input name="city" value="{{ $location ? $location->city : '' }}" type="text" maxlength="255">
                    </div>
                </div>
                <div class="field">
                    <label>{{ __('State / Province / Region') }}</label>
                    <div class="ui input">
                        <input name="state" value="{{ $location ? $location->state : '' }}" type="text" maxlength="255">
                    </div>
                </div>
            </div>
            <div class="two fields">
                <div class="required field">
                    <label>{{ __('Zip / Postal code') }}</label>
                    <div class="ui input">
                        <input name="zip" type="text" value="{{ $location ? $location->zip : '' }}" maxlength="15">
                    </div>
                </div>
                <div class="field">
                    <label>{{ __('District name') }}</label>
                    <div class="ui input">
                        <input name="district" type="text" value="{{ $location ? $location->district : '' }}">
                    </div>
                    <small style="display: block; padding-top: 0.25rem; text-align: right;">{{ __('Location common district name. The one people often refer to, when speaking about particular area in the city.') }}</small>
                </div>
            </div><br />
            <div class="field">
                <label>
                    {{ __('How to find the entrance') }} <i class="help circle icon link" data-content="{{ __('Please provide detailed information on how to find an entrance door in the location, so new people don\'t have a problem to reach you when arrive.') }}" data-variation="wide"></i>
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ __('Max :chars chars', ['chars' => 255]) }}</em>
                </label>
                <textarea name="how_to_find" style="height: 60px; min-height: 60px;" maxlength="255">{{ $location ? $location->how_to_find : '' }}</textarea>
            </div>

            <div class="ui stackable doubling grid">
                <div class="eight wide column">
                    <button class="ui red right labeled icon button" type="submit">
                        <i class="marker icon"></i>
                        {{ $location ? __('Update') : __('Save') }} &amp; {{ __('Map') }}
                    </button>
                </div>
                <div class="eight wide column right aligned">
                    @if ($location)
                    <p style="padding-top: 0.78571em;">
                        <a
                            id="delete"
                            href="{{ url('api/v1/classes', [$group->id, 'location', $location->id]) }}"
                            title=""
                            data-deleting-label="{{ __('Deleting...') }}"
                            data-error-label="{{ __('There was a problem deleting this location.') }}"
                        >{{ __('Delete this location?') }}</a>
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
                <div class="header">{{ __('Adding/editing location') }}</div>
                <p>{{ __('Specify detailed location of your class. This is going to help potential customers to find your class easier.') }}</p>
                <p>{{ __('If it is common in your city to use district names when speaking about particular area, fill in "District" field.') }}</p>
                <p>{{ __('After you fill in your address and hit "Save &amp; Map" button, interactive map will be displayed to help you specify very precise location. This extremely useful for new customers, since it is not always obvious how to reach your room for the first time.') }}</p>
            </div>
        </div>
    </div>
</div>

<div id="delete-modal" class="ui small modal">
    <div class="header">{{ __('Do you really wish to delete this location?') }}</div>
    <div class="content">
        <p>{{ __('Pricing and schedule sessions tied to this location will be removed, too.') }}</p>
    </div>
    <div class="actions">
        <div class="ui basic cancel button">{{ __('Cancel') }}</div>
        <div class="ui red right labeled icon ok button">
            {{ __('Delete') }}
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