<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

	<title></title>
    {!! HTML::style( asset('styles/bootstrap.css') ) !!}
    {!! HTML::style( asset('styles/landing.css') ) !!}

    <meta name="description" content=""/>
    <meta name="keywords" content="private classes, courses, instructors, coaches, teachers, private courses, classes"/>
    <meta property="og:image" content="{{ asset('images/avatar.jpg') }}"/>
    <meta property="og:title" content=""/>
    <meta property="og:description" content=""/>
    <meta property="og:url" content="{{ url('/') }}"/>
    <meta property="og:site_name" content=""/>
    <meta property="og:type" content="website"/>
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:url" content="{{ url('/') }}"/>
    <meta name="twitter:title" content=""/>
    <meta name="twitter:description" content=""/>
    <meta name="twitter:image" content="{{ asset('images/avatar.jpg') }}"/>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}"/>
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}"/>
</head>
<body>
<div class="container-fluid pt-xs-1 landing--main-container landing--primary-background">
    <header class="raw clearfix">
        <div class="col-xs-6">
            <a href="{{ url('/') }}" title="" class="btn btn-link text-capitalize">{{ config('custom.code') }}</a>
        </div>
        <div class="col-xs-6 text-xs-right">
            <a href="{{ url('/login') }}" title="" class="btn btn-outline-secondary text-muted">Login</a>
        </div>
    </header>
    <div class="container">
        <main class="row clearfix landing--main">
            <div class="col-md-5 col-lg-4 landing--main-column">
                <div class="text-xs-center text-md-left pos-md-rel landing--value-proposition">
                    <h1>Your class page online</h1>
                    <h2 class="font-weight-normal mb-xs-2">Information that matters in front of your customers. Clear and simple.</h2>
                    <div class="d-block">
                        <a href="{{ url('/register?ref=landing-top') }}" title="" class="btn btn-primary landing--btn-action">Create your class page</a>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-lg-8 text-xs-center landing--main-column">
                <img src="https://s24.postimg.org/4j8s8hnud/mobile_apps_lineup.png" alt="" class="hidden-md-up mt-xs-2 w-100 landing--product-image" />
                <img src="https://s27.postimg.org/9tcp4faj7/img_desktop_full.png" alt="" class="hidden-sm-down ml-lg-1 landing--product-image" />
            </div>
        </main>
    </div>
</div>
<div class="container pt-xs-3 pt-md-3">
    <div class="mx-md-auto landing--features">
        <div class="pos-xs-rel mb-xs-1 text-xs-center landing--features-heading">
            <span class="pos-xs-rel px-xs-1">Benefits</span>
        </div>
        <div class="row mb-xs-1 pt-xs-2 mb-md-3 pb-xs-1 landing--feature">
            <div class="col-md-7">
                <div class="pos-xs-rel landing--feature-text">
                    <h3>People explore your class, effortless</h3>
                    <p>Share your class description, contact info, location, schedule and prices. Let customers learn what your class is all about.</p>
                </div>
            </div>
            <div class="hidden-sm-down text-sm-center col-md-5 col-lg-4 offset-lg-1">
                <img src="https://s24.postimg.org/r2u7ydxx1/Untitled_4.jpg" alt="">
            </div>
        </div>
        <div class="row mb-xs-1 pt-xs-2 mb-md-3 pb-xs-1 landing--feature">
            <div class="col-md-6 offset-md-1 push-md-5 pr-md-0 col-lg-7 offset-lg-0">
                <div class="pos-xs-rel landing--feature-text">
                    <h3>Mobile or computer, your class page is accessible from anywhere</h3>
                    <p>People spend more time on desktop and mobile combined<sup>*</sup>. Your class page is always reachable. Customers can get in touch with you from any device, any time.</p>
                    <cite class="d-block pos-md-abs text-xs-right">eMarketer, April 2015, US</cite>
                </div>
            </div>
            <div class="text-xs-center col-md-5 pull-md-7">
                <img src="https://s24.postimg.org/r5e3l81kl/Untitled_2.jpg" alt="">
            </div>
        </div>
        <div class="row mb-xs-1 pt-xs-2 mb-md-3 pb-xs-1 landing--feature">
            <div class="col-md-7 col-lg-6">
                <div class="pos-xs-rel landing--feature-text">
                    <h3>Personalise your class</h3>
                    <p>
                        Add photos from your classes and trainings.
                        <br class="hidden-md-down" />
                        Demonstrate uniqueness.
                    </p>
                </div>
            </div>
            <div class="text-xs-center col-md-5 col-lg-6">
                <img src="https://s24.postimg.org/4feys8id1/Untitled_3.jpg" alt="">
            </div>
        </div>
        <div class="row mb-xs-1 pt-xs-2 mb-md-3 pb-xs-1 landing--feature">
            <div class="col-md-6 offset-md-1 push-md-5 pr-md-0">
                <div class="pos-xs-rel landing--feature-text">
                    <h3>Add multiple locations</h3>
                    <p>Run classes in numerous locations? List them all on the map. Give people an option to choose whether to attend classes near home or close to the office after work.</p>
                </div>
            </div>
            <div class="text-xs-center col-md-5 pull-md-7">
                <img src="https://s24.postimg.org/syh09jmr9/Untitled_1.jpg" alt="">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-xs-3 landing--primary-background">
    <div class="container text-xs-center py-xs-3">
        <h4 class="mb-2">Excellent way to showcase your class online</h4>
        <a href="{{ url('/register?ref=landing-bottom') }}" title="" class="btn btn-primary landing--btn-action">Create your class page</a>
    </div>
</div>
<footer class="container-fluid py-xs-2 landing--footer">
    <div class="container py-md-2">
        <ul>
            <li class="d-inline-block">
                <a href="{{ url('/privacy-policy') }}" title="">Privacy Policy</a>
            </li>

            @if (env('BLOG_URL'))
                <li class="d-inline-block ml-2">
                    <a href="{{ env('BLOG_URL') }}" title="">Blog</a>
                </li>
            @endif
        </ul>
        <p>Our mission at <span class="text-capitalize">{{ config('custom.code') }}</span> is to help coaches, instructors and teachers organise and run private classes.</p>
        <p><a href="{{ url('/register?ref=landing-footer') }}" title="">Join us in this mission</a> &#11088;</p>
    </div>
</footer>

{{ HTML::script('https://code.jquery.com/jquery-3.1.1.slim.min.js', array(
    'integrity' => 'sha256-/SIrNqv8h6QGKDuNoLGA4iret+kyesCkHGzVUUV0shc=',
    'crossorigin' => 'anonymous'
)) }}

<script>window.jQuery || document.write('<script src="{{ asset('scripts/vendor/jquery-3.1.1.slim.min.js') }}"><\/script>')</script>
{{ HTML::script( asset('scripts/landing.js') ) }}
</body>
</html>