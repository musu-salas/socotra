<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<title>@yield('title')</title>

    {!! HTML::style( asset('semantic.css') ) !!}
    {!! HTML::style( asset('socotra.css') ) !!}

    @yield('styles')

    @yield('meta')
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}"/>
</head>
<body>
@yield('content')

@include('googleAnalytics');
@include('facebookPixel', [
    'user' => $user ?? null
])

{!! HTML::script( '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js' ) !!}
<script>window.jQuery || document.write('<script src="{!! asset('vendor/jquery-1.11.2.min.js') !!}"><\/script>')</script>
{!! HTML::script( asset('semantic.js') ) !!}

@yield('scripts')
</body>
</html>
