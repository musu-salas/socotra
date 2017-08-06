@extends('app')

@section('title', __('Password Reset') . ' · ' . config('app.name'))

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
                        Password Reset
                        <div class="sub header">{{ __('Provide us with your account email address and a new password.') }}</div>
                    </h2>

                    <form class="ui form" role="form" method="POST" action="{{ url('password/reset') }}">
                        {!! csrf_field() !!}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="ui centered grid">
                            <div class="doubling column row">
                                <div class="column">

                                    <div class="ui error message {{ count($errors) ? 'visible' : '' }}">
                                        <div class="content">
                                            <ul class="list">
                                                @if(count($errors) > 0)
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="ui icon input">
                                            <input type="email" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ $email or old('email') }}">
                                            <i class="at icon"></i>
                                        </div>
                                    </div>

									<div class="field">
                                        <div class="ui icon input">
											<input type="password" placeholder="{{ __('New Password') }}" name="password">
											<i class="lock icon"></i>
										</div>
									</div>

									<div class="field">
                                        <div class="ui icon input">
											<input type="password" placeholder="{{ __('Confirm New Password') }}" name="password_confirmation">
											<i class="lock icon"></i>
										</div>
									</div>

									<div class="ui submit red button">{{ __('Reset Password') }}</div>
                                </div>
                            </div>
                        </div>
					</form>
                </div>
            </div>
        </div>

        @if(count($errors) > 0)
            <div class="ui basic segment center aligned">
                <div class="ui header">
                    <p class="sub header">{!! __('If you still can not reset your password, try to :link once again.', [ 'link' => '<a class="inline-link" href="' . url('password/email') . '" title="' . __('Reset password') . '">' . __('request password reset link') . '</a>' ]) !!}
                </div>
            </div>
        @endif
    </div>
</div>
<br /><br /><br />
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.password.js') ) !!}
@endsection