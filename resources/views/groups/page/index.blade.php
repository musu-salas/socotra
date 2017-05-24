@extends('app')

@section('title', trans('group/profile.page_title', [
    'title' => $group->title,
    'creative_field' => ($group->creative_field2) ? $group->creative_field2 : $group->creative_field1,
    'city' => $location->city,
    'country' => $location->country->name
]))

@section('content')

@include('topbar', [
    'user' => $user ?? null,
    'activePage' => null,
    'isStatic' => true
])

<div>
    @if($user && $user->id == $group->user_id || !$group->is_public)
        <div class="ui visible {{ $group->is_public ? 'success' : 'warning'  }} message" style="position: relative; border-radius: 0; margin: 0; padding: 0.75em 0.5em; border-bottom: solid 1px rgba(39, 41, 43, 0.15); box-shadow: none;">
            <p style="text-align: center; color: rgba(0, 0, 0, 0.8);">
                @if($group->is_public)
                    <i class="icon check circle"></i>{{ trans('group/profile.your_class_is_public') }}<em>!</em>
                    {!! trans('group/profile.your_class_sharable_link', [ 'link' => '<span style="color: #3c763d;">' . url('/classes', [$group->id, $location->id]) . '</span>' ]) !!}

                @elseif($user && $user->id == $group->user_id)
                    <i class="icon hide"></i>{{ trans('group/profile.your_class_is_not_public') }} &mdash; <a href="{{ url('/home/classes', [$group->id]) }}" title="">{{ trans('group/profile.add_missing_information') }}</a>

                @else
                    <i class="icon hide"></i>{{ trans('group/profile.page_is_not_public') }} &mdash; {{ trans('group/profile.information_is_not_complete') }}
                @endif
            </p>
        </div>
    @endif

    <div class="ui page stackable doubling grid" style="padding-top: 1rem;">
        <div class="wide column">
            <div class="ui stackable grid center aligned">
                <div
                    id="cover-parent"
                    class="wide padded column"
                    data-height="400"
                    style="{{ ($photos_count) ? '' : 'display: table !important;' }} height: 400px; padding-top: 0 !important; padding-bottom: 0 !important; width: 100% !important; overflow: hidden; position: relative;"
                >
                    @if($photos_count)
                        <img
                            id="cover"
                            class="ui image"
                            data-src="{{ $group->cover_photo->large_src }}"
                            data-width="{{ $group->cover_photo->large_width }}"
                            data-height="{{ $group->cover_photo->large_height }}"
                            data-offset="{{ $group->cover_photo_offset }}%"
                            style="width: 100%; top: {{ $group->cover_photo_offset . '%' }};"
                        >

                        <div id="cover-controls" class="no-edit"><!--
                        -->@if($user && $user->id == $group->user_id)
                                <a class="default" id="reposition-cover" href="#" title="">
                                    <i class="move icon"></i>
                                    {{ trans('group/profile.reposition_cover_photo') }}
                                </a><!--

                                --><a class="position save" id="save-cover"  href="#" title="">
                                    <i class="checkmark icon"></i>
                                    {{ trans('buttons.save') }}
                                </a><!--
                                --><a class="position" id="cancel-cover" href="#" title="">{{ trans('buttons.cancel') }}</a><!--
                            --><br /><!--
                        -->@endif<!--

                        -->@if($user && $user->id == $group->user_id)
                                <a class="default" href="{{ url('/home/classes', [$group->id, 'photos']) }}" title="">
                                    <i class="write icon"></i>
                                    {{ trans('group/profile.manage_photos') }}
                                </a>

                            @else
                                <a class="default" href="#" title="See all pictures" data-gallery>
                                    <i class="photo icon"></i>
                                    {{ trans_choice('group/profile.see_all_photos', $photos_count, ['photos' => $photos_count]) }}
                                </a>
                            @endif
                        </div>

                        <div class="ui loader"></div>

                    @else
                        <div style="display: table-cell; vertical-align: middle;">
                            <i class="icon photo huge {{ ($user && $user->id == $group->user_id) ? 'link' : '' }}" onclick="{{ ($user && $user->id == $group->user_id) ? 'window.location = \'' . url('/home/classes', [$group->id, 'photos']) . '\';' : '' }}"></i>


                            <p style="padding-top: 1rem;">

                                @if($user && $user->id == $group->user_id)
                                    <a href="{{ url('/home/classes', [$group->id, 'photos']) }}" title=""><strong>{{ trans('group/profile.upload_photos') }}</strong></a>

                                @else
                                    {{ trans('group/profile.no_pictures_available') }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ui divider"></div>
            <div class="ui stackable doubling grid">
                <div class="ui form two column row">
                    <div class="eleven wide column">
                        <div class="ui header">
                            @if($group->title)
                                <h1 style="margin: 0; padding: 0; font-size: 1.5rem;">
                                    {{ $group->title }}

                                    @if($user && $user->id == $group->user_id)
                                        <sup>
                                            <a href="{{ url('home/classes', [$group->id, 'overview']) }}" title="" style="margin-left: 0.5rem; font-size: 0.9285rem; font-weight: 400;">
                                                <i class="write icon"></i><span>{{ trans('buttons.edit') }}</span>
                                            </a>
                                        </sup>
                                    @endif
                                </h1>

                            @elseif($user && $user->id == $group->user_id)
                                <p>
                                    <a href="{{ url('/home/classes', [$group->id, 'overview']) }}" title="">
                                        <i class="write icon"></i><span>{{ trans('group/profile.add_class_title') }}</span>
                                    </a>
                                </p>
                            @endif

                            @if($group->uvp)
                                <h2 class="sub header" style="margin-top: 0.5rem;">{{ $group->uvp }}</h2>


                            @elseif($user && $user->id == $group->user_id)
                                <h2 class="sub header" style="margin-top: 0.5rem;">
                                    <a href="{{ url('/home/classes', [$group->id, 'overview']) }}" title="">
                                        <i class="write icon"></i><span>{{ trans('group/profile.add_uvp') }}</span>
                                    </a>
                                </h2>
                            @endif
                        </div>

                        <div>
                            @foreach($location->keywords as $keyword)
                                <div class="ui label">{{ $keyword }}</div>
                            @endforeach
                        </div>

                        <div class="ui divider"></div>

                        @if ($group->description)
                            <div class="field">
                                <label>
                                    {{ trans('group/profile.about_class') }}

                                    @if($user && $user->id == $group->user_id)
                                        <a href="{{ url('/home/classes', [$group->id, 'overview']) }}" title="" style="margin-left: 1rem; font-weight: 400;">
                                            <i class="write icon"></i><span>{{ trans('buttons.edit') }}</span>
                                        </a>
                                    @endif
                                </label>

                                <div id="description" class="collapsed">
                                    <p><?php echo nl2br($group->description, false); ?></p>
                                </div>
                            </div><br />

                        @elseif($user && $user->id == $group->user_id)
                            <div>
                                <a href="{{ url('/home/classes', [$group->id, 'overview']) }}" title="">
                                    <i class="write icon"></i><span>{{ trans('group/profile.add_class_description') }}</span>
                                </a>
                            </div><br /><br />
                        @endif

                        <div class="field">
                            <label>
                                Class Location

                                @if($user && $user->id == $group->user_id)
                                    <a href="{{ url('/home/classes', [$group->id, 'location', $location->id]) }}" title="" style="margin-left: 1rem; font-weight: 400;">
                                        <i class="write icon"></i><span>{{ trans('buttons.edit') }}</span>
                                    </a>
                                @endif
                            </label>

                            @if($location->is_full)
                                <div class="ui selection dropdown icon address {{ count($locations) === 1 ? 'single' : '' }}">
                                    <i class="marker icon"></i>
                                    <div class="text">{{ $location->full_address }}</div>

                                    @if(count($locations) > 1)
                                        <i class="dropdown icon"></i>
                                        <div class="menu">
                                            @foreach($locations as $item)
                                                <div class="item" data-value="{{ $item->id }}">{{ $item->full_address }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                            @elseif($user && $user->id === $group->user_id)
                                <p>
                                    {{ trans('group/profile.location_not_complete') }}, <a href="{{ url('/home/classes', [$group->id, 'location', $location->id]) }}" title="" style="margin-left: 0.5rem;"><i class="write icon"></i>{{ trans('group/profile.fix_address') }}</a>.
                                </p>
                            @endif

                            @if($location->latlng)
                                <div id="map" data-is-full="{{ $location->is_full }}" style="margin: 1rem 0 3rem; min-height: 14rem; position: relative;">
                                    <div id="map-image" style="min-height: 10rem;"></div>

                                    @if($location->is_full)
                                        <div style="text-align: right; padding-right: 0.5rem;"><em>{{ trans('group/profile.click_map_zoom') }}</em></div>
                                    @endif

                                    @if($location->how_to_find)
                                        <i class="icon circular blue info circle" data-content="{{ $location->how_to_find }}" data-position="left center" data-variation="wide" style="position: absolute; top: 1rem; right: 1rem;"></i>
                                    @endif
                                </div>

                            @elseif($user && $user->id === $group->user_id && $location->is_full)
                                <p style="padding-top: 1rem;">
                                    <a href="{{ url('/home/classes', [$group->id, 'location', $location->id, 'map']) }}" title=""><i class="write icon"></i>{{ trans('group/profile.review_location_map') }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="five wide column">
                        <div class="field">
                            <label>
                                {{ trans('group/profile.attendance_fees') }}

                                @if($user && $user->id == $group->user_id && count($pricing))
                                    <a href="{{ url('/home/classes', [$group->id, 'pricing']) }}" title="" style="margin-left: 1rem; font-weight: 400;">
                                        <i class="write icon"></i><span>{{ trans('buttons.edit') }}</span>
                                    </a>
                                @endif
                            </label>
                        </div>

                        @if(count($pricing))
                            <table class="ui red striped table">
                                <tbody>
                                    @foreach(array_values(array_sort($pricing, function($p) { return $p->pivot->price; })) as $price)
                                        <tr data-content="{{ $price->description }}" data-position="top center">
                                            <td>{{ $location->currency->symbol }}{{ $price->pivot->price }}</td>
                                            <td>{{ $price->title }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- TODO: Use to highlight any option.
                                    <tr class="disabled">
                                        <td class="right aligned">Free</td>
                                        <td>First time try</td>
                                    </tr>
                                    --}}
                                </tbody>
                            </table>

                        @else
                            @if($user && $user->id == $group->user_id)
                                <p>
                                    <a href="{{ url('/home/classes', [$group->id, 'pricing']) }}" title="">
                                        <i class="write icon"></i>{{ trans('group/profile.add_class_fees') }}
                                    </a>
                                </p>
                            @else
                                <p>{{ trans('group/profile.no_fees_available') }}</p>
                            @endif
                        @endif

                        @if($group->is_public)
                            <div style="margin-top: 4rem;">
                                <div class="ui divider"></div>
                                <div class="field">
                                    <label style="display: block;">
                                        {{ trans('group/profile.coach') }}

                                        @if($user && $user->id === $group->user_id)
                                            <a href="{{ url('home/settings') }}" title="" style="float: right; margin-right: 0.5rem; font-weight: 400;">
                                                <i class="write icon"></i>{{ trans('buttons.edit') }}
                                            </a>
                                        @endif
                                    </label>
                                </div>
                                <div style="margin-top: 1rem; text-align: center;">
                                    <img class="ui image centered circular tiny" src="{{ $group->owner->avatar_src }}" />
                                    <p style="margin-top: 0.5rem;">{{ $group->owner->fullname }}</p>
                                    <p><strong>{{ trans('group/profile.ready_to_start') }}</strong> <br />{{ trans('group/profile.lets_get_in_touch') }}</p>
                                    <button class="ui labeled icon button red" data-toggle="contact-modal">
                                        <i class="comment icon"></i>
                                        {{ trans('group/profile.contact_coach') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div><br />

    <div class="ui page stackable doubling grid">
        <div class="wide column ui form">
            <div class="field">
                <label>
                    {{ trans('group/profile.schedule') }}

                    @if($user && $user->id == $group->user_id && count($weekly_schedule))
                        <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id]) }}" title="" style="margin-left: 1rem; font-weight: 400;">
                            <i class="write icon"></i><span>{{ trans('buttons.edit') }}</span>
                        </a>
                    @endif
                </label>
            </div>

            <p>
                @if($user && $user->id == $group->user_id && !count($weekly_schedule))
                    <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id]) }}" title="">
                        <i class="write icon"></i>{{ trans('group/profile.add_class_schedule') }}
                    </a>

                @elseif(!count($weekly_schedule))
                    {{ trans('group/profile.schedule_not_available') }}

                @endif
            </p>

            <div class="ui grid" style="{{ count($weekly_schedule) ? '' : 'opacity: 0.3;' }}">

                <?php $x = 0; $nextRow = 1; $dayNames = [trans_choice('date.monday', 2), trans_choice('date.tuesday', 2), trans_choice('date.wednesday', 2), trans_choice('date.thursday', 2), trans_choice('date.friday', 2), trans_choice('date.saturday', 2), trans_choice('date.sunday', 2)] ?>
                <div class="doubling two column row">

                    @foreach($weekly_schedule as $day => $schedule)
                        <div class="column">
                            <table class="ui celled compact small table" style="{{ count(array_filter($schedule)) ? '' : 'background: none;' }}">
                                <thead>
                                    <tr>
                                        <th colspan="2">{{ $dayNames[$day] }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($schedule as $row)
                                        @if ($row)
                                            <tr data-content="{{ $row['description'] }}" data-title="{{ $row['title'] }}" data-variation="wide" data-position="top center">
                                                <td class="five wide">{{ $row['starts'] }} &mdash; {{ $row['ends'] }}</td>
                                                <td>{{ $row['title'] }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="five wide">&nbsp;</td>
                                                <td></td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    <?php if($x === $nextRow) { $nextRow = $nextRow + 2; ?>
                    </div>
                    <div class="doubling two column row">
                    <?php } ?>

                    <?php $x++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div><br /><br />

    <div class="ui page stackable doubling grid">
        <div class="wide column ui form">
            <div class="field">
                <label>
                    {{ trans('group/profile.photos') }}

                    @if($user && $user->id == $group->user_id && $photos_count)
                        <a href="{{ url('/home/classes', [$group->id, 'photos']) }}" title="" style="margin-left: 1rem; font-weight: 400;">
                            <i class="write icon"></i><span>{{ trans('buttons.edit') }}</span>
                        </a>

                    @elseif($photos_count > 4)
                        &mdash; <a href="#" title="See all pictures" data-gallery>{{ trans_choice('group/profile.see_all_photos', 5, ['photos' => $photos_count]) }}</a>
                    @endif
                </label>
            </div>


            @if($photos_count)
                <div class="ui stackable grid center aligned">
                    <?php $photos = $group->photos->slice(0, 4); ?>

                    @foreach($photos as $key => $photo)
                        <div class="four wide column" style="position: relative; cursor: pointer;" data-gallery="{{ $photo->id }}">
                            <img class="ui image" src="{{ $photo->thumbnail_src }}">

                            @if($photos_count > 4 && $key == count($photos) - 1)
                                <div style="position: absolute; bottom: 14px; left: 30px; right: 30px; padding: 7px 0; border-top: solid 1px rgba(255, 255, 255, 0.5); text-align: center; color: rgba(255, 255, 255, 0.8);"><strong>{{ trans_choice('group/profile.see_all_photos', 5, ['photos' => $photos_count]) }}</strong></div>
                            @endif
                        </div>
                    @endforeach

                    @for ($i = 0; $i < 4 - ($photos_count - count($photos)); $i++)
                        <div class="four wide column"></div>
                    @endfor

                    <?php unset($photos, $empty_photo_blocks); ?>
                </div><br /><br />

            @elseif($user && $user->id == $group->user_id)
                <p>
                    <a href="{{ url('/home/classes', [$group->id, 'photos']) }}" title="">
                        <i class="write icon"></i>{{ trans('group/profile.upload_photos') }}
                    </a>
                </p>

            @else
                {{ trans('group/profile.no_pictures_available') }}

            @endif

            <!--
            @if(count($amenities) > 0)
                <div class="field">
                    <label>{{ trans('group/profile.amenities') }}</label>
                </div>
                <div class="ui grid">
                    <div class="doubling four column row">
                        <div class="column">

                            <?php $i = 0; $nextCheck = 5; ?>
                            @foreach($amenities as $amenity)
                                <div class="item">
                                    <i class="aligned right triangle icon"></i>{{ $amenity->name }}
                                </div>

                            <?php if($i === $nextCheck) { $nextCheck = $nextCheck + 6; ?>
                            </div><div class="column">
                            <?php } ?>

                            <?php $i++; ?>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endif
            -->
        </div>
    </div>

    <div class="ui page stackable doubling grid">
        <div class="wide column ui form">
            <div class="ui divider"></div>
            <div class="field">
                <label>{{ trans('group/profile.interested') }}</label>
            </div>
            <p>{{ trans('group/profile.try_or_ask') }} <br />{{ trans('group/profile.contact_coach_details') }}</p>
            <button class="ui labeled icon button red" data-toggle="contact-modal">
                <i class="comment icon"></i>
                {{ trans('group/profile.contact_coach') }}
            </button>
        </div>
    </div>
    <br /><br /><br /><br />
</div>

@if(!$group->is_public && (!$user || $user->id != $group->user_id))
    @include('groups.page.private')
@endif

@include('groups.page.gallery')

@if($location->latlng)
    @include('groups.page.map', [
        'location' => $location
    ])
@endif

@if($group->is_public)
    @include('groups.page.contact', [
        'group' => $group
    ])
@endif

<input type="hidden" name="group_id" value="{{ $group->id }}" />
<input type="hidden" name="location_id" value="{{ $location->id }}" />

@if($user && $user->id == $group->user_id)
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
@endif
@endsection

@section('styles')
{!! HTML::style( asset('photoswipe/photoswipe.css') ) !!}
{!! HTML::style( asset('photoswipe/default-skin/default-skin.css') ) !!}
@endsection

@section('scripts')
{!! HTML::script( 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . env('GOOGLE_MAPS_KEY') ) !!}
{!! HTML::script( asset('jquery-ui_draggable-only.min.js') ) !!}
{!! HTML::script( asset('photoswipe/photoswipe.min.js') ) !!}
{!! HTML::script( asset('photoswipe/photoswipe-ui-default.min.js') ) !!}
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-display.js') ) !!}

@if($group->is_public)
    {!! HTML::script( asset('socotra.group-display.contact.js') ) !!}
@endif
@endsection