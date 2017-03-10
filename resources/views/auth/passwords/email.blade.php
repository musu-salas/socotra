@extends('app')

@section('title', 'Request a new Password')

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
                        Request Password Reset
                        <div class="sub header">Enter the email address associated with your account, and we'll email you with a link to reset your password.</div>
                    </h2>

                    @if(session('status'))
                        <div class="ui segment icon message positive left aligned">
                            <p>{{ session('status') }}</p>
                        </div>
                    @endif

                    <form class="ui form" role="form" method="POST" action="{{ url('/password/email') }}">
                        {!! csrf_field() !!}

                        <div class="ui centered grid">
                            <div class="doubling column row">
                                <div class="column">
                                    <div class="ui icon error message {{ count($errors) > 0 ? 'visible' : '' }}">
                                        <i class="warning circle icon"></i>
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
                                            <input type="email" placeholder="Email Address" name="email" value="{{ old('email') }}">
                                            <i class="at icon"></i>
                                        </div>
                                    </div>

                                    <div class="ui submit red button">Send Reset Link</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="ui basic segment center aligned">
            <div class="ui header">
                <p class="sub header">Or, if you still remember it, <a class="inline-link" href="{{ url('/login') }}" title="">Log in here</a>.</p>
            </div>
        </div>
    </div>
</div>
<br /><br /><br />
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.password.js') ) !!}
@endsection