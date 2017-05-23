@extends('app')

@section('title', 'Create class — ' . config('app.name'))

@section('content')

@include('topbar', [
    'user' => $user,
    'activePage' => null
])

<br /><br /><br />
<div>
    <div class="ui page stackable doubling grid">
        <div class="wide column">
            <div class="ui stackable doubling grid">
                <div class="one column row" style="padding-bottom: 0;">
                    <div class="wide column">
                        <h2 class="ui header">
                            <i class="marker icon"></i>
                            <div class="content">
                                {{ trans('group/new.create_class_page') }}
                                <div class="sub header">{{ trans('group/new.expand_publicity') }}</div>
                            </div>
                        </h2>
                        <div class="ui divider"></div>
                    </div>
                </div>

                <form class="ui form eleven wide column" method="post" action="" autocomplete="off">
                    <input autocomplete="false" name="hidden" type="text" style="display: none;" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    @if (count($errors) > 0)
                        <div class="ui icon error message" style="display:block !important; margin-top: 0;">
                            <i class="warning circle icon"></i>
                            <div class="content">
                                <ul class="list">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="two fields">
                        <div class="required field">
                            <label>{{ trans('group/new.general_discipline') }}</label>
                            <div class="ui input">
                                <input name="creative_field1" type="text" placeholder="{{ trans('group/new.general_discipline_examples') }}">
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('group/new.subdiscipline') }} <em style="font-weight: normal;">({{ trans('form.optional') }})</em></label>
                            <div class="ui input">
                                <input name="creative_field2" type="text" placeholder="{{ trans('group/new.subdiscipline_examples') }}">
                            </div>
                        </div>
                    </div>

                    <div class="required field">
                        <label>{{ trans('form.city') }}</label>
                        <div class="ui input">
                            <input type="hidden" name="location" value="{{ $user->location }}" />
                            <input type="text" name="location_text" placeholder="{{ trans('group/new.city_examples') }}" name="location" data-types="(cities)" value="{{ $user->location_text }}">
                        </div>
                    </div>

                    <div class="two fields">
                        <div class="field">
                            <label>{{ trans('group/new.attendance_fee') }} <em style="font-weight: normal;">({{ trans('form.optional') }})</em> <i class="help circle icon link" data-content="{{ trans('group/new.attendance_fee_description') }}" data-variation="large"></i></label>
                            <div class="ui labeled input">
                                <div class="ui compact floating selection dropdown" data-scoped style="background: #E8E8E8; color: rgba(0, 0, 0, 0.6);">
                                    <input type="hidden" name="currency_id" value="{{ $currencies[0]->id }}">
                                    <div class="text" style="margin-right: 5rem;">{{ $currencies[0]->code }}</div>
                                    <i class="dropdown icon" style="top: 0.9rem;"></i>
                                    <div class="menu">
                                        @foreach ($currencies as $currency)
                                            <div class="item" data-value="{{ $currency->id }}">{{ $currency->code }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <input name="single_fee" type="text" maxlength="9">
                            </div>
                        </div>
                    </div><br /><br />

                    <div class="ui submit red button">{{ trans('group/new.create_class') }}<i>!</i></div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script( 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . env('GOOGLE_MAPS_KEY') ) !!}
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.new.js') ) !!}
@endsection