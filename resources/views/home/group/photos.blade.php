@extends('app')

@section('title', __('Manage class photos') . ' · ' . config('app.name'))

@section('content')

@include('topbar', [
    'user' => $user,
    'activePage' => 'classes'
])

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
                <span>{{ __('Add Photo') }}</span>

                <input id="photos-file-input" type="file" name="photos[]" multiple style="position: absolute; top: 0; right: 0; width: 100%; height: 100%; outline: none; background: none; border: 0; opacity: 0;">
            </form>
            <div id="counter" class="ui disabled button" style="position: absolute; top: 1rem; right: 1rem; color: rgba(0, 0, 0, 0.6) !important; opacity: 1 !important; background: none !important;">
                <span>{{ count($group->photos) }}</span> / {{ config('custom.class.max_photos') }}
            </div>
        </div>
        <p style="margin: 1rem 0; color: #aaa; font-style: italic; font-weight: 400;">{!! __('* We recommend uploading <code>jpg</code>/<code>jpeg</code> photos, where you clearly demonstrate activity of your class.') !!}</p>
        <div id="generic-alert" class="ui error message hidden"></div>
        <div id="uploaded-alert" class="ui positive message hidden"></div>
        <div id="failed-alert" class="ui error message hidden">
            <div class="header">{{ __('These files failed to upload:') }}</div>
            <ul class="list"></ul>
        </div>
        <div
            id="photos"
            data-translations='{ "group/photos.is_cover_photo": "{{ __('This is your page cover photo') }}", "group/photos.set_cover_photo": "{{ __('Set as your page cover photo') }}" }'
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
                    <div class="ui icon button {{ ($photo->is_cover) ? 'yellow' : '' }}" data-content="{{ ($photo->is_cover) ? __('This is your page cover photo') : __('Set as your page cover photo') }}" data-position="top center">
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