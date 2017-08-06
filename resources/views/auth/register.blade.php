@extends('app')

@section('title', __('Sign up') . ' · ' . config('app.name'))

@section('content')
<div>
    <div class="ui centered page stackable doubling grid">
        <div class="ui six wide centered column">
            <br />
            <h1 class="ui center aligned header">{{ config('app.name') }}</h1>
        </div>
        <div class="ui eight wide column">
            <div class="ui segment">
                <div class="ui basic segment center aligned">
                    <h2 class="ui header">
                        {!! __('Sign up to :name<em>!</em>', [ 'name' => config('app.name') ]) !!}
                        <div class="sub header">{{ __('Create your class page online') }}</div>
                    </h2>

                    @if (Input::has('error'))
                        <div class="ui error message visible" style="margin-bottom: 0; text-align: left;">
                            <div class="content">
                                <ul class="list">
                                    <li>
                                        @if(Input::get('error') == 'access_denied')
                                            {{ __('Facebook cancelled connection. Please try again.') }}

                                        @elseif(Input::get('error') == 'missing_user_data')
                                            {{ __('Facebook was not able to provide necessary data. Please try again.') }}

                                        @else
                                            {{ __('There was a problem connecting to your Facebook. Please try again.') }}
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="ui basic segment" style="margin-top: 0;">
                        <a href="{{ url('socialize/facebook') }}" class="ui large facebook button">
                            <i class="facebook icon"></i>
                            {{ __('Sign up with Facebook') }}
                        </a>
                    </div>

                    <form class="ui form" role="form" method="POST" action="{{ url('register') }}" autocomplete="off">
                        <input autocomplete="false" name="hidden" type="text" style="display: none;" />
                        {!! csrf_field() !!}

                        <div class="ui centered grid">
                            <div class="doubling column row">
                                <div class="column">
                                    <h4 class="ui horizontal header divider">{{ __('Or') }}</h4>

                                    @if (!count($errors))
                                        <div class="ui basic segment center aligned">
                                            <a class="ui basic large button" href="{{ url('register') }}" onclick="$(this).parent().hide().next().show().prev().remove(); return false;">
                                                <i class="mail outline icon"></i>
                                                {{ __('Sign up with Email') }}
                                            </a>
                                        </div>
                                    @endif

                                    <div style="{{ count($errors) ? '' : 'display: none;' }}">
                                        <div class="ui error message {{ count($errors) ? 'visible' : '' }}">
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

                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="text" placeholder="{{ __('First name') }}" name="first_name" value="{{ old('first_name') }}">
                                                <i class="user icon"></i>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="text" placeholder="{{ __('Last name') }}" name="last_name" value="{{ old('last_name') }}">
                                                <i class="user icon"></i>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="email" placeholder="{{ __('Email Address') }}" name="email" value="{{ old('email') }}">
                                                <i class="at icon"></i>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="password" placeholder="{{ __('Password') }}" name="password">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="password" placeholder="{{ __('Confirm Password') }}" name="password_confirmation">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>

                                        <br />
                                        <div class="field">
                                            <label>{{ __('In which city do you live?') }} <i class="help circle icon link" data-content="General geographical location that matches your interest for classes." data-variation="large"></i></label>
                                            <div class="ui input">
                                                <input type="hidden" name="location" value="{{ old('location') }}" />
                                                <input type="text" name="location_text" placeholder="{{ __('e.g. Manhattan, NY / Berlin, DE / Bali, Indonesia') }}" data-types="(cities)" value="{{ old('location_text') }}">
                                            </div>
                                        </div>

                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="newsletter" value="1" checked>
                                                <label>{{ __('Keep me updated with community progress') }}</label>
                                            </div>
                                        </div>

                                        <br />
                                        <div class="fluid ui submit red button">{{ __('Sign up') }}</div>
                                    </div>

                                    <h5 class="ui header">
                                        <div class="sub header">
                                            {!! __('By signing up, I agree to :link.', [ 'link' => '<a href="' . url('privacy-policy') . '" target="blank" title="' . __('Privacy Policy') . '">' . __('Privacy Policy') . '</a>' ]) !!}
                                        </div>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="ui basic segment center aligned">
            <div class="ui header">
                <p class="sub header">{!! __('Already a :name member? :link.', [ 'name' => config('app.name'), 'link' => '<a class="inline-link" href="' . url('login') . '" title="' . __('Login') . '">' . __('Log in here') . '</a>' ]) !!}</p>
            </div>
        </div>
    </div>
</div>
<br /><br /><br />
@endsection

@section('scripts')
{!! HTML::script( 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en&key=' . env('GOOGLE_MAPS_KEY') ) !!}
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.register.js') ) !!}
@endsection