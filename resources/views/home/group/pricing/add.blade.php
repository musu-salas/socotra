@extends('app')

@if($price)
    @section('title', __('Edit class pricing') . ' · ' . config('app.name'))

@else
    @section('title', __('Add class pricing') . ' · ' . config('app.name'))
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
            'active' => 'pricing'
        ])
    </div>
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header">
            @if($price)
                {{ __('Edit Pricing') }}
            @else
                {{ __('Add Pricing') }}
            @endif
        </h3>

        <form class="ui form" action="" method="post">
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

            <div class="required field">
                <label for="title">
                    {{ __('Title') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ __('Max :chars chars', ['chars' => 50]) }}</em>
                </label>
                <div class="ui input">
                    <input id="title" value="{{ old('title') ? old('title') : ($price ? $price->title : '') }}" name="title" type="text" placeholder="{{ __('e.g. Single attendance, Monthly flex, Annual pass...') }}" maxlength="50">
                </div>
            </div>
            <div class="field">
                <label for="description">
                    {{ __('Short description') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ __('Max :chars chars', ['chars' => 255]) }}</em>
                </label>
                <textarea id="description" name="description" style="height: 60px; min-height: 60px;" maxlength="255">{{ old('description') ? old('description') : ($price ? $price->description : '') }}</textarea>
            </div>

            <div class="required field">
                <label>{{ __('Price by location') }}</label>
                <p>{{ __('Select a location below to assign a price.') }}</p>

                <table class="ui compact table" style="margin: 0;">
                    <tbody>
                        @foreach ($locations as $i => $location)
                        <tr class="">
                            <td class="collapsing">
                                <input name="prices[{{ $i }}][location]" value="{{ $location->id }}" type="hidden" />

                                <div class="ui checked checkbox">
                                    <input name="prices[{{ $i }}][checked]" type="checkbox" {{ isset($pricesByLocation[$location->id]) ? 'checked="checked"' : '' }} />
                                </div>
                            </td>
                            <td>{{ $location->address_line_1 }}, {{ $location->zip }}, {{ $location->city }}</td>
                            <td class="collapsing">
                                <div class="ui labeled input">
                                    <div class="ui label"> <var class="currency-symbol">{{ $location->currency->symbol }}</var> </div>
                                    <input id="price_{{ $i }}" name="prices[{{ $i }}][price]"  value="{{ $pricesByLocation[$location->id] ?? '' }}" type="text" style="width: 7rem; padding-right: 1rem !important;">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="ui stackable doubling grid">
                <div class="eight wide column">
                    <button class="ui button red" type="submit">
                        @if($price)
                            {{ __('Update') }}
                        @else
                            {{ __('Save') }}

                        @endif
                    </button>
                </div>
                <div class="eight wide column right aligned">
                    @if ($price)
                    <p style="padding-top: 0.78571em;">
                        <a
                            id="delete"
                            href="{{ url('api/v1/classes', [$group->id, 'pricing', $price->id]) }}"
                            title=""
                            data-deleting-label="{{ __('Deleting...') }}"
                            data-error-label="{{ __('There was a problem deleting this pricing.') }}"
                        >{{ __('Delete this pricing?') }}</a>
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
                <div class="header">{{ __('Manage pricing') }}</div>
                <p>{!! __('List all available pricing options for your class. These could be single class fee, weekly access, monthly subscription or any other variations you offer. <br /><br />If you have multiple class locations, it is also possible to define different prices per location.') !!}</p>
            </div>
        </div>
    </div>
</div>

<div id="delete-modal" class="ui small modal">
    <div class="header">{{ __('Do you really wish to delete this pricing?') }}</div>
    <div class="content">
        <p>{{ __('Pricing will be removed from all locations.') }}</p>
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
{!! HTML::script( asset('socotra.group-manage.pricing.js') ) !!}
@endsection