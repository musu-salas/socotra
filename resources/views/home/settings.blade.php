@extends('app')

@section('title', __('Account Settings') . ' · ' . config('app.name'))

@section('content')

@include('topbar', [
    'user' => $user,
    'activePage' => 'settings'
])

<br /><br /><br />
<div>
    <div class="ui page stackable doubling grid">
        <div class="wide column">
            <div class="ui stackable doubling grid">
                <div class="one column row">
                    <div class="wide column">
                        <h2 class="ui header">
                            <i class="settings icon"></i>
                            <div class="content">
                                {{ __('Account Settings') }}
                                <div class="sub header">{{ __('Manage your preferences') }}</div>
                            </div>
                        </h2>
                        <div class="ui divider"></div>
                    </div>
                </div>
                <form class="ui form two column row" role="form" method="POST" action="{{ url('home/settings' . (Input::get('followTo') ? '?followTo=' . Input::get('followTo') : '')) }}" autocomplete="off">
                    <input autocomplete="false" name="hidden" type="text" style="display: none;" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="followTo" value="{{ Input::get('followTo') }}">

                    <div class="five wide column">
                        <div class="ui basic segment">
                            <div class="ui top attached large label">
                                {{ __('Profile Photo') }}
                                <a id="avatar-change-link" href="{{ __('Change') }}" style="float: right; font-weight: normal; color: #009fda;">
                                    <i class="icon upload"></i>{{ __('Change') }}
                                </a>
                            </div>
                            <div style="text-align: center;">
                                <div id="avatar">
                                    <img class="ui small circular image" src="{{ $user->avatar_src }}">
                                    <i class="spinner loading icon big"></i>
                                </div>
                            </div>
                        </div>

                        <div class="ui basic segment">
                            <div class="ui top attached large label">{{ __('Trust and Verification') }}</div>
                            <div>

                                @if($user->facebook_id)
                                    <div class="field">
                                        <label><i class="checkmark icon"></i> {{ __('Your account is verified') }}<i>!</i><br /></label>
                                    </div>

                                    <span data-content="{{ __('Your account is securely connected with Facebook. Thanks for making :app safe and trusty place.', [ 'app' => config('app.name') ]) }}">
                                        <span class="ui basic facebook button">
                                            <i class="icon facebook"></i>
                                            {{ __('Facebook connected') }}
                                        </span>
                                    </span>
                                @else
                                    <div class="field">
                                        <label style="">{{ __('Please verify your account by connecting to Facebook. We respect privacy and trust, and never spam or post on your behalf.') }}<br /></label>
                                    </div>

                                    @if (Input::has('error'))
                                        <div class="ui error message visible">
                                            <div class="content">
                                                <ul class="list">
                                                    <li>
                                                        @if(Input::get('error') == 'access_denied')
                                                            {{ __('Facebook cancelled connection. Please try again.') }}

                                                        @elseif(Input::get('error') == 'missing_user_data')
                                                            {{ __('Facebook was not able to provide necessary data. Please try again.') }}

                                                        @elseif(Input::get('error') == 'already_user')
                                                            {{ __('It seems you have another account with us. Try to logout and login using Facebook to access it.') }}

                                                        @else
                                                            {{ __('There was a problem connecting to your Facebook. Please try again.') }}
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    <a class="ui facebook button" href="{{ url('socialize/facebook') }}" data-content="{{ __('We wish to make our community trusty and safe for everyone. By connecting with Facebook we verify your account.') }}">
                                        <i class="icon facebook"></i>
                                        {{ __('Connect Facebook') }}
                                    </a>
                                @endif

                                <p><br />{{ __('Getting a verified account is a great way to help build trust in our community.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="eleven wide column">

                        @if (Session::has('success-message'))
                        <div class="ui icon success message visible">
                            <i class="close icon"></i>
                            {{ Session::get('success-message') }}
                        </div>
                        @endif

                        <div class="ui segment">
                            <div class="ui top attached large label">{{ __('Main information') }}</div>
                            <div>

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
                                    <label>{{ __('First name') }}</label>
                                    <div class="ui icon input">
                                        <input type="text" name="first_name" value="{{ $user->first_name }}">
                                        <i class="user icon"></i>
                                    </div>
                                </div>
                                <div class="required field">
                                    <label>{{ __('Last name') }}</label>
                                    <div class="ui icon input">
                                        <input type="text" name="last_name" value="{{ $user->last_name }}">
                                        <i class="user icon"></i>
                                    </div>
                                </div>
                                <div class="required field {{ $user->email == config('services.facebook.empty_email') ? 'error' : '' }}">
                                    <label>{{ __('Email address') }}</label>
                                    <div class="ui icon input">
                                        <input type="email" name="email" value="{{ $user->email == config('services.facebook.empty_email') ? '' : $user->email }}">
                                        <i class="at icon"></i>
                                    </div>
                                </div>
                                <div class="field">
                                    <label>{{ __('In which city do you live?') }} <i class="help circle icon link" data-content="{{ __('General geographical location that matches your interest for classes.') }}" data-variation="large"></i></label>
                                    <div class="ui input">
                                        <input type="hidden" name="location" value="{{ $user->location }}" />
                                        <input type="text" name="location_text" placeholder="{{ __('e.g. New York, NY / London, UK / Bali, Indonesia') }}" data-types="(cities)" value="{{ $user->location_text }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ui segment">
                            <div class="ui top attached large label">{{ __('Newsletter') }}</div>
                            <div>
                                <div class="field">
                                    <div class="ui checkbox">
                                        @if($user->newsletter == 1)
                                            <input type="checkbox" name="newsletter" checked>
                                        @else
                                            <input type="checkbox" name="newsletter">
                                        @endif
                                        <label>{{ __('Keep me updated with community progress') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($user->has_password)
                            <div class="ui segment" style="padding-bottom: 22px;">
                                <div class="ui accordion">
                                    <div class="ui top attached label large title">
                                        <i class="icon dropdown"></i>
                                        {{ __('Update account password') }}
                                    </div>
                                    <div class="content">
                                        <div class="required field">
                                            <label>{{ __('Current Password') }}</label>
                                            <div class="ui icon input">
                                                <input type="password" placeholder="{{ __('Password') }}" name="password">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>
                                        <div class="required field">
                                            <label>{{ __('New Password') }}</label>
                                            <div class="ui icon input">
                                                <input type="password" placeholder="{{ __('Password') }}" name="new_password">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>
                                        <div class="required field">
                                            <label>{{ __('Confirm New Password') }}</label>
                                            <div class="ui icon input">
                                                <input type="password" placeholder="{{ __('Password') }}" name="new_password_confirmation">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($user->created_at == $user->updated_at && !count($user->myGroups))
                            <div class="ui submit red button">{!! __('Save &amp; Create your class') !!}</div>

                        @else
                            <div class="ui submit red button">{{ __('Save') }}</div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="avatar-modal" class="ui modal">
    <div class="header">{{ __('Crop Your Photo') }}</div>
    <div class="content" style="padding: 0;">
        <div class="cropper">
            <div id="avatar-modal-cropper" class="cropper-container"></div>
        </div>
    </div>
    <div class="actions">
        <div class="ui approve button">{{ __('Save') }}</div>
        <div class="ui cancel button">{{ __('Cancel') }}</div>
    </div>
</div>

<form id="avatar-form" method="post" enctype="multipart/form-data" action="" data-label-error="{{ __('There was a problem uploading profile photo.') }}" style="visibility: hidden;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="file" name="avatar">
</form>

<input type="hidden" name="user_id" value="{{ $user->id }}" />
@endsection

@section('scripts')
{!! HTML::script( 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en&key=' . env('GOOGLE_MAPS_KEY') ) !!}
{!! HTML::script( asset('vendor/crop.js') ) !!}
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.settings.js') ) !!}
@endsection

@section('styles')
{!! HTML::style( asset('cropper.css') ) !!}
@endsection