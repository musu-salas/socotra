@extends('app')

@section('title', __('Log in') . ' · ' . config('app.name'))

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
                        {{ __('Welcome back') }} &nbsp; ;)
                        <div class="sub header">{{ __('Please log in to your :name account.', [ 'name' => config('app.name') ]) }}</div>
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
                            {{ __('Log in with Facebook') }}
                        </a>
                    </div>

                    <form class="ui form" role="form" method="POST" action="{{ url('login') }}">
                        {!! csrf_field() !!}

                        <div class="ui centered grid">
                            <div class="doubling column row">
                                <div class="column">
                                    <p class="ui horizontal header divider">{{ __('Or') }}</p>

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

                                    <div class="ui grid">
                                        <div class="two column row">
                                            <div class="column">
                                                <div class="field">
                                                    <div class="ui checkbox">
                                                        <input type="checkbox" name="remember" checked>
                                                        <label>{{ __('Remember me') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="column right aligned">
                                                <a href="{{ url('password/reset') }}" title="{{ __('Forgot password?') }}'">{{ __('Forgot password?') }}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <br />
                                    <div class="fluid ui submit red button">{{ __('Log In') }}</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="ui basic segment center aligned">
            <div class="ui header">
                <p class="sub header">{!! __('Don\'t have an account? :link.', [ 'link' => '<a class="inline-link" href="' . url('register') . '" title="' . __('Register') . '">' . __('Sign up here') . '</a>' ]) !!}</p>
            </div>
        </div>
    </div>
</div>
<br /><br /><br />
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.login.js') ) !!}
@endsection