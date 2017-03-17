@extends('app')

@section('title', trans('group/photos.page_title'))

@section('content')
<div class="ui fixed borderless menu" style="box-shadow: 0 0 1px rgba(39, 41, 43, 0.15);">
    <div class="ui page stackable doubling grid" style="margin: 0;">
        <a class="item" href="{{ url('/') }}" title="{{ config('app.name') }}">
            <strong>{{ config('app.name') }}</strong>
        </a>

        @include('home.navigation', [
            'user' => $user,
            'active' => 'classes'
        ])
    </div>
</div>

@include('home.group.statusbar', [
    'group' => $group,
    'menu' => $menu
])

<div class="ui stackable doubling grid" style="padding-top: 80px; margin: 0;">
    <div id="steps-column" class="three wide column" style="background: #eee;">
        @include('home.group.menu', [
            'group' => $group,
            'menu' => $menu,
            'active' => 'photos'
        ])
    </div>
    <div class="thirteen wide column" style="padding-top: 1rem !important;">
        <form id="upload-button" class="ui button red {{ $group->photos_count >= config('custom.class.max_photos') ? 'disabled' : '' }}" method="post" enctype="multipart/form-data" action="" style="position: relative;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <i class="cloud upload icon"></i>
            {{ trans('group/photos.add_photo') }}

            <input type="file" name="photo" style="position: absolute; top: 0; right: 0; width: 100%; height: 100%; outline: none; background: none; border: 0; opacity: 0;">
        </form>
        <div class="ui button loading basic" style="display: none;">&nbsp;</div>
        <br /><br />

        {{-- TODO: Show different text if staging env. --}}
        {{--IF ends_with(head(explode('.', url(''))), 'mybucket-name'))
            <p style="padding-bottom: 1rem; color: red; font-style: italic; font-weight: 400;">
                While in testing mode, please test with <code>jpg</code>/<code>jpeg</code> photos under 1Mb.
            </p>
        ELSE --}}
            <p style="padding-bottom: 1rem; color: #aaa; font-style: italic; font-weight: 400;">{!! trans('group/photos.add_photo_description') !!}</p>
        {{-- ENDIFELSE --}}

        <div id="counter" class="ui disabled button" style="position: absolute; top: 1rem; right: 1rem; color: rgba(0, 0, 0, 0.6) !important; opacity: 1 !important; background: none !important;"></div>

        <div id="errors" class="ui error message {{ count($errors) ? '' : 'hidden' }}" style="margin-top: 0;">
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

        <div id="success" class="ui icon success message {{ Session::has('success-message') ? '' : 'hidden' }}">
            {{ Session::get('success-message') }}
        </div>

        <div>
            @foreach($group->photos as $photo)
            <div class="ui left floated segment hoverable" style="margin-top: 0;">
                <input name="photo_id" value="{{ $photo->id }}" type="hidden" />
                <div class="ui inverted dimmer">
                    <div class="ui text loader">Deleting</div>
                </div>
                <img class="ui fluid image" src="{{ $photo->thumbnail_src }}" style="width: 224px;">
                <div class="ui icon button"><i class="trash icon"></i></div>
                <div class="ui icon button {{ $group->cover_photo && $group->cover_photo->id == $photo->id ? 'yellow' : '' }}" data-content="{{ $group->cover_photo && $group->cover_photo->id == $photo->id ? trans('group/photos.is_cover_photo') : trans('group/photos.set_cover_photo') }}" data-position="top center"><i class="star icon purple"></i></div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<input type="hidden" name="group_id" value="{{ $group->id }}" />
<input type="hidden" name="max_photos" value="{{ config('custom.class.max_photos') }}" />
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.photos.js') ) !!}
@endsection