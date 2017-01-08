@extends('app')

@section('title', trans('group/pricing.page_titles.' . ($price ? 'edit' : 'add')))

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
        <h3 class="ui header">{{ trans('group/pricing.' . ($price ? 'edit' : 'add') . '_pricing') }}</h3>

        <form class="ui form" action="" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="ui error message {{ $errors->has() ? 'visible' : '' }}">
                <div class="content">
                    <ul class="list">
                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="required field">
                <label for="title">
                    {{ trans('form.title') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ trans('form.max_chars', ['chars' => 50]) }}</em>
                </label>
                <div class="ui input">
                    <input id="title" value="{{ old('title') ? old('title') : ($price ? $price->title : '') }}" name="title" type="text" placeholder="{{ trans('group/pricing.title_samples') }}" maxlength="50">
                </div>
            </div>
            <div class="field">
                <label for="description">
                    {{ trans('form.short_description') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ trans('form.max_chars', ['chars' => 255]) }}</em>
                </label>
                <textarea id="description" name="description" style="height: 60px; min-height: 60px;" maxlength="255">{{ old('description') ? old('description') : ($price ? $price->description : '') }}</textarea>
            </div>

            <div class="required field">
                <label>{{ trans('group/pricing.price_location') }}</label>
                <p>{{ trans('group/pricing.select_location') }}</p>

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
                                    <input id="price_{{ $i }}" name="prices[{{ $i }}][price]"  value="{{ isset($pricesByLocation[$location->id]) ? $pricesByLocation[$location->id] : '' }}" type="text" style="width: 7rem; padding-right: 1rem !important;">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="ui stackable doubling grid">
                <div class="eight wide column">
                    <button class="ui button red" type="submit">{{ trans('buttons.' . ($price ? 'update' : 'save')) }}</button>
                </div>
                <div class="eight wide column right aligned">
                    @if ($price)
                    <p style="padding-top: 0.78571em;">
                        <a
                            id="delete"
                            href="{{ url('/api/v1/classes', [$group->id, 'pricing', $price->id]) }}"
                            title=""
                            data-deleting-label="{{ trans('group/pricing.deleting') }}"
                            data-error-label="{{ trans('group/pricing.problem_deleting') }}"
                        >{{ trans('group/pricing.delete') }}</a>
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
                <div class="header">{{ trans('group/pricing.helpers.manage_pricing') }}</div>
                <p>{!! trans('group/pricing.helpers.manage_pricing_description') !!}</p>
            </div>
        </div>
    </div>
</div>

<div id="delete-modal" class="ui small modal">
    <div class="header">{{ trans('group/pricing.really_delete') }}</div>
    <div class="content">
        <p>{{ trans('group/pricing.really_delete_info') }}</p>
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
{!! HTML::script( asset('socotra.group-manage.pricing.js') ) !!}
@endsection