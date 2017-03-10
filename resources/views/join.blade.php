@extends('app')

@section('title', 'Not available in your country')

@section('content')
<br /><br />
<div class="ui centered page stackable doubling grid">
    <div class="ui ten wide centered column">
        <div class="ui segment piled" style="padding: 1rem 2rem;">
            <h3 class="ui header center aligned"><br />{{ config('app.name') }}</h3>
            <p>
                We are not available in your country yet.
                However, please feel free to sign up with us below to stay up to date with cool things we make here preparing to launch in your country.
            </p>
            <blockquote>
                Here's what we do:<br />
                <em>A adipiscing metus a luctus ornare turpis imperdiet massa fames felis suscipit donec ad ultrices rutrum proin justo dolor a pulvinar ullamcorper.</em>
            </blockquote>
            <br />

            <form class="ui form segment" style="margin-bottom: 1rem; padding-bottom: 0.5rem;" method="post" action="" autocomplete="off">
                <input autocomplete="false" name="hidden" type="text" style="display: none;" />
                <div id="errors" class="ui error message" style="margin-top: 0;"></div>

                <div class="required field">
                    <label>In which city do you run classes? <i class="help circle icon link" data-content="General geographical location that matches your interest for classes." data-variation="large"></i></label>
                    <div class="ui icon input">
                        <input type="hidden" name="location" />
                        <input type="text" name="location_text" placeholder="e.g. Manhattan, NY / Berlin, DE / Bali, Indonesia" data-types="(cities)">
                        <i class="marker icon"></i>
                    </div>
                </div>
                <div class="two fields">
                    <div class="required field">
                        <label>General creative field <i class="help circle icon link" data-content="Lorem ipsum dolor sit amet, consectetur adipiscing elit." data-variation="large"></i></label>
                        <div class="ui input">
                            <input name="creative_field1" type="text" placeholder="e.g. dancing, piano, fitness, math, design...">
                        </div>
                    </div>
                    <div class="field">
                        <label>Sub creative field (optional) <i class="help circle icon link" data-content="Lorem ipsum dolor sit amet, consectetur adipiscing elit." data-variation="large"></i></label>
                        <div class="ui input">
                            <input name="creative_field2" type="text" placeholder="Salsa for dancing, interior design for design...">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>Your website, link to a Facebook page or similar (optional) <i class="help circle icon link" data-content="Lorem ipsum..." data-variation="large"></i></label>
                    <div class="ui input">
                        <input name="website" type="text">
                    </div>
                </div>
                <div class="two fields">
                    <div class="required field">
                        <label>Your first name</label>
                        <div class="ui input">
                            <input name="first_name" type="text">
                        </div>
                    </div>
                    <div class="required field">
                        <label>Last name</label>
                        <div class="ui input">
                            <input name="last_name" type="text">
                        </div>
                    </div>
                </div>
                <div class="required field" style="margin-bottom: 0.5rem;">
                    <label>Email address</label>
                    <div class="ui icon input">
                        <input name="email" type="email">
                        <i class="inverted circular share icon red link submit" style="opacity: 1;"></i>
                    </div>
                </div>
                <small style="position: absolute; top: 110%; right: 1rem; color: grey;">It is absolutely free.</small>
            </form>
        </div>
    </div>
</div>
<br /><br /><br /><br />

<div id="pioneer-modal" class="ui small modal">
    <div class="header">Thanks for stopping by again<em>!</em><br />And no worries, we remember you.</div>
    <div class="content">
        <p>You have already signed up on <span id="pioneer-modal-date"></span>.</p>
        <p>We are going to keep you updated with cool things we make here preparing to launch in your country.</p>
    </div>
    <div class="actions">
        <div class="ui positive right labeled icon button">
            Got it
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>

<input type="hidden" name="_token" value="{{ csrf_token() }}">
@endsection

@section('scripts')
{!! HTML::script( 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . env('GOOGLE_MAPS_KEY') ) !!}
{!! HTML::script( asset('semantic.js') ) !!}
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.join.js') ) !!}
@endsection