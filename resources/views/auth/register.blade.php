@extends('app')

@section('title', 'Sign up')

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
                        Sign up to {{ config('app.name') }}<i>!</i>
                        <div class="sub header">A trusted community marketplace for people to list, discover and enrol in classes around the city.</div>
                    </h2>

                    @if (Input::has('error'))
                        <div class="ui error message visible" style="margin-bottom: 0; text-align: left;">
                            <div class="content">
                                <ul class="list">
                                    <li>
                                        @if(Input::get('error') == 'access_denied')
                                            {{ trans('socialize.access_denied') }}

                                        @elseif(Input::get('error') == 'missing_user_data')
                                            {{ trans('socialize.missing_user_data') }}

                                        @else
                                            {{ trans('socialize.problem') }}
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="ui basic segment" style="margin-top: 0;">
                        <a href="{{ url('/socialize/facebook') }}" class="ui large facebook button">
                            <i class="facebook icon"></i>
                            Sign up with Facebook
                        </a>
                    </div>

                    <form class="ui form" role="form" method="POST" action="{{ url('/register') }}" autocomplete="off">
                        <input autocomplete="false" name="hidden" type="text" style="display: none;" />
                        {!! csrf_field() !!}

                        <div class="ui centered grid">
                            <div class="doubling column row">
                                <div class="column">
                                    <h4 class="ui horizontal header divider">
                                        Or
                                    </h4>

                                    @if (!count($errors))
                                        <div class="ui basic segment center aligned">
                                            <a class="ui basic large button" href="{{ url('/register') }}" onclick="$(this).parent().hide().next().show().prev().remove(); return false;">
                                                <i class="mail outline icon"></i>
                                                Sign up with Email
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
                                                <input type="text" placeholder="First name" name="first_name" value="{{ old('first_name') }}">
                                                <i class="user icon"></i>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="text" placeholder="Last name" name="last_name" value="{{ old('last_name') }}">
                                                <i class="user icon"></i>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="email" placeholder="Email Address" name="email" value="{{ old('email') }}">
                                                <i class="at icon"></i>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="password" placeholder="Password" name="password">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui icon input">
                                                <input type="password" placeholder="Confirm Password" name="password_confirmation">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>

                                        <br />
                                        <div class="field">
                                            <label>In which city do you live? <i class="help circle icon link" data-content="General geographical location that matches your interest for classes." data-variation="large"></i></label>
                                            <div class="ui input">
                                                <input type="hidden" name="location" value="{{ old('location') }}" />
                                                <input type="text" name="location_text" placeholder="e.g. Manhattan, NY / Berlin, DE / Bali, Indonesia" data-types="(cities)" value="{{ old('location_text') }}">
                                            </div>
                                        </div>

                                        <div class="field">
                                            <div class="ui checkbox">
                                                <input type="checkbox" name="newsletter" value="1" checked>
                                                <label>Keep me updated with community progress</label>
                                            </div>
                                        </div>

                                        <br />
                                        <div class="fluid ui submit red button">Sign up</div>
                                    </div>

                                    <h5 class="ui header">
                                        <div class="sub header">
                                            By signing up, I agree to <a href="{{ url('/privacy-policy') }}" target="blank" title="Privacy Policy">Privacy Policy</a>.
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
                <p class="sub header">Already a {{ config('app.name') }} member? <a class="inline-link" href="{{ url('/login') }}" title="Register">Log in here</a>.</p>
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