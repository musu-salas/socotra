@extends('app')

@section('title', trans('user/settings.page_title'))

@section('content')

@include('topbar', [
    'user' => $user,
    'activePage' => 'classes'
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
                                {{ trans('user/settings.account_settings') }}
                                <div class="sub header">{{ trans('user/settings.manage_preferences') }}</div>
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
                                {{ trans('user/settings.profile_photo') }}
                                <a id="avatar-change-link" href="#" style="float: right; font-weight: normal; color: #009fda;">
                                    <i class="icon upload"></i>{{ trans('buttons.change') }}
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
                            <div class="ui top attached large label">{{ trans('user/settings.trust_verification') }}</div>
                            <div>

                                @if($user->facebook_id)
                                    <div class="field">
                                        <label><i class="checkmark icon"></i> {{ trans('user/settings.account_verified') }}<i>!</i><br /></label>
                                    </div>

                                    <span data-content="{{ trans('user/settings.account_securely_connected') }}">
                                        <span class="ui basic facebook button">
                                            <i class="icon facebook"></i>
                                            {{ trans('user/settings.facebook_connected') }}
                                        </span>
                                    </span>
                                @else
                                    <div class="field">
                                        <label style="">{{ trans('user/settings.verify_account') }}<br /></label>
                                    </div>

                                    @if (Input::has('error'))
                                        <div class="ui error message visible">
                                            <div class="content">
                                                <ul class="list">
                                                    <li>
                                                        @if(Input::get('error') == 'access_denied')
                                                            {{ trans('socialize.access_denied') }}

                                                        @elseif(Input::get('error') == 'missing_user_data')
                                                            {{ trans('socialize.missing_user_data') }}

                                                        @elseif(Input::get('error') == 'already_user')
                                                            {{ trans('socialize.already_user') }}

                                                        @else
                                                            {{ trans('socialize.problem') }}
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    <a class="ui facebook button" href="{{ url('/socialize/facebook') }}" data-content="{{ trans('user/settings.trusty_community') }}">
                                        <i class="icon facebook"></i>
                                        {{ trans('user/settings.facebook_connect') }}
                                    </a>
                                @endif

                                <p><br />{{ trans('user/settings.getting_verified_account') }}</p>
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
                            <div class="ui top attached large label">{{ trans('user/settings.main_info') }}</div>
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
                                    <label>{{ trans('form.first_name') }}</label>
                                    <div class="ui icon input">
                                        <input type="text" name="first_name" value="{{ $user->first_name }}">
                                        <i class="user icon"></i>
                                    </div>
                                </div>
                                <div class="required field">
                                    <label>{{ trans('form.last_name') }}</label>
                                    <div class="ui icon input">
                                        <input type="text" name="last_name" value="{{ $user->last_name }}">
                                        <i class="user icon"></i>
                                    </div>
                                </div>
                                <div class="required field {{ $user->email == config('services.facebook.empty_email') ? 'error' : '' }}">
                                    <label>{{ trans('form.email_address') }}</label>
                                    <div class="ui icon input">
                                        <input type="email" name="email" value="{{ $user->email == config('services.facebook.empty_email') ? '' : $user->email }}">
                                        <i class="at icon"></i>
                                    </div>
                                </div>
                                <div class="field">
                                    <label>{{ trans('user/settings.city_you_live') }} <i class="help circle icon link" data-content="{{ trans('user/settings.geographical_location') }}" data-variation="large"></i></label>
                                    <div class="ui input">
                                        <input type="hidden" name="location" value="{{ $user->location }}" />
                                        <input type="text" name="location_text" placeholder="{{ trans('user/settings.city_examples') }}" data-types="(cities)" value="{{ $user->location_text }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ui segment">
                            <div class="ui top attached large label">{{ trans('user/settings.newsletter') }}</div>
                            <div>
                                <div class="field">
                                    <div class="ui checkbox">
                                        @if($user->newsletter == 1)
                                            <input type="checkbox" name="newsletter" checked>
                                        @else
                                            <input type="checkbox" name="newsletter">
                                        @endif
                                        <label>{{ trans('user/settings.keep_updated_progress') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($user->has_password)
                            <div class="ui segment" style="padding-bottom: 22px;">
                                <div class="ui accordion">
                                    <div class="ui top attached label large title">
                                        <i class="icon dropdown"></i>
                                        {{ trans('user/settings.update_password') }}
                                    </div>
                                    <div class="content">
                                        <div class="required field">
                                            <label>{{ trans('form.current_password') }}</label>
                                            <div class="ui icon input">
                                                <input type="password" placeholder="{{ trans('form.password') }}" name="password">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>
                                        <div class="required field">
                                            <label>{{ trans('form.new_password') }}</label>
                                            <div class="ui icon input">
                                                <input type="password" placeholder="{{ trans('form.password') }}" name="new_password">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>
                                        <div class="required field">
                                            <label>{{ trans('form.confirm_new_password') }}</label>
                                            <div class="ui icon input">
                                                <input type="password" placeholder="{{ trans('form.password') }}" name="new_password_confirmation">
                                                <i class="lock icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($user->created_at == $user->updated_at && !count($user->myGroups))
                            <div class="ui submit red button">{{ trans('user/settings.save_create_class') }}</div>

                        @else
                            <div class="ui submit red button">{{ trans('buttons.save') }}</div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="avatar-modal" class="ui modal">
    <div class="header">{{ trans('user/settings.crop_photo') }}</div>
    <div class="content" style="padding: 0;">
        <div class="cropper">
            <div id="avatar-modal-cropper" class="cropper-container"></div>
        </div>
    </div>
    <div class="actions">
        <div class="ui approve button">{{ trans('buttons.save') }}</div>
        <div class="ui cancel button">{{ trans('buttons.cancel') }}</div>
    </div>
</div>

<form id="avatar-form" method="post" enctype="multipart/form-data" action="" data-label-error="{{ trans('user/settings.problem_uploading_photo') }}" style="visibility: hidden;">
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