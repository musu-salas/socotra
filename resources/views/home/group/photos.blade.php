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
    <div id="page-container" class="thirteen wide column" style="padding-top: 1rem !important;">
        <div style="poition: relative;">
            <form
                id="photos-form"
                class="ui button red {{ $group->photos_count >= config('custom.class.max_photos') ? 'disabled' : '' }}"
                {{ $group->photos_count >= config('custom.class.max_photos') ? 'disabled' : '' }}
                method="post"
                enctype="multipart/form-data"
                action=""
                style="position: relative;"
            >
                <i class="cloud upload icon"></i>
                <span>{{ trans('group/photos.add_photo') }}</span>

                <input id="photos-file-input" type="file" name="photos[]" multiple style="position: absolute; top: 0; right: 0; width: 100%; height: 100%; outline: none; background: none; border: 0; opacity: 0;">
            </form>
            <div id="counter" class="ui disabled button" style="position: absolute; top: 1rem; right: 1rem; color: rgba(0, 0, 0, 0.6) !important; opacity: 1 !important; background: none !important;">
                <span>{{ count($group->photos) }}</span> / {{ config('custom.class.max_photos') }}
            </div>
        </div>
        <p style="margin: 1rem 0; color: #aaa; font-style: italic; font-weight: 400;">{!! trans('group/photos.add_photo_description') !!}</p>
        <div id="generic-alert" class="ui error message hidden"></div>
        <div id="uploaded-alert" class="ui positive message hidden"></div>
        <div id="failed-alert" class="ui error message hidden">
            <div class="header">{{ trans('group/photos.failed_photos') }}</div>
            <ul class="list"></ul>
        </div>
        <div
            id="photos"
            data-translations='{ "group/photos.is_cover_photo": "{{ trans('group/photos.is_cover_photo') }}", "group/photos.set_cover_photo": "{{ trans('group/photos.set_cover_photo') }}" }'
        >
            @foreach($group->photos as $photo)
            <div
                class="ui left floated segment hoverable"
                style="margin-top: 0;"
                data-id="{{ $photo->id }}"
                data-is-cover="{{ $photo->is_cover }}"
            >
                <div style="overflow: hidden; width: 14rem; height: 9rem;">
                    <img class="ui fluid image" src="{{ $photo->thumbnail_src ?? $photo->original_src }}" style="width: 100%; width: 100%; -webkit-transform: translateY(-50%); transform: translateY(-50%); top: 50%;">
                    <div class="ui icon button"><i class="trash icon"></i></div>
                    <div class="ui icon button {{ ($photo->is_cover) ? 'yellow' : '' }}" data-content="{{ ($photo->is_cover) ? trans('group/photos.is_cover_photo') : trans('group/photos.set_cover_photo') }}" data-position="top center">
                        <i class="star icon purple"></i>
                    </div>
                </div>
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
{!! HTML::script( asset('scripts/group-edit.js') ) !!}
@endsection