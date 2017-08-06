@extends('app')

@section('title', __(':title · :discipline in :Location', [
    'title' => $group->title,
    'discipline' => ($group->creative_field2) ? $group->creative_field2 : $group->creative_field1,
    'location' => ($location->district ? $location->district . ', ' : '') . $location->city,
    'district' => $location->country->name
    
]) . ' · ' . config('app.name'))

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
                    <i class="icon check circle"></i>{{ __('Your class is public') }}<em>!</em>
                    {!! __('Here is the link `:link` to share on your social networks', [ 'link' => '<span style="color: #3c763d;">' . url('classes', [$group->id, $location->id]) . '</span>' ]) !!}

                @elseif($user && $user->id == $group->user_id)
                    <i class="icon hide"></i>{!! __('Your class is not public &mdash; :link', [ 'link' => '<a href="' . url('home/classes', [$group->id]) . '" title="' . ucfirst(__('add missing information')) . '">' . __('add missing information') . '</a>' ]) !!}

                @else
                    <i class="icon hide"></i>{{ __('Page is not public yet &mdash; information on this page is not complete') }}
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
                                <a class="default" id="reposition-cover" href="#" title="{{ __('Reposition') }}">
                                    <i class="move icon"></i>
                                    {{ __('Reposition cover photo') }}
                                </a><!--

                                --><a class="position save" id="save-cover" href="#" title="{{ __('Save') }}">
                                    <i class="checkmark icon"></i>
                                    {{ __('Save') }}
                                </a><!--
                                --><a class="position" id="cancel-cover" href="#" title="{{ __('Cancel') }}">{{ __('Cancel') }}</a><!--
                            --><br /><!--
                        -->@endif<!--

                        -->@if($user && $user->id == $group->user_id)
                                <a class="default" href="{{ url('home/classes', [$group->id, 'photos']) }}" title="{{ __('Manage photos') }}">
                                    <i class="write icon"></i>
                                    {{ __('Manage photos') }}
                                </a>

                            @else
                                <a class="default" href="#" title="{{ _n('[1,4] See all photos|[5,*] See all :photos photos', $photos_count, ['photos' => $photos_count]) }}" data-gallery>
                                    <i class="photo icon"></i>
                                    {{ _n('[1,4] See all photos|[5,*] See all :photos photos', $photos_count, ['photos' => $photos_count]) }}
                                </a>
                            @endif
                        </div>

                        <div class="ui loader"></div>

                    @else
                        <div style="display: table-cell; vertical-align: middle;">
                            <i class="icon photo huge {{ ($user && $user->id == $group->user_id) ? 'link' : '' }}" onclick="{{ ($user && $user->id == $group->user_id) ? 'window.location = \'' . url('home/classes', [$group->id, 'photos']) . '\';' : '' }}"></i>


                            <p style="padding-top: 1rem;">

                                @if($user && $user->id == $group->user_id)
                                    <a href="{{ url('home/classes', [$group->id, 'photos']) }}" title="{{ __('Upload photos') }}"><strong>{{ __('Upload photos') }}</strong></a>

                                @else
                                    {{ __('No photos available yet') }}
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
                                            <a href="{{ url('home/classes', [$group->id, 'overview']) }}" title="{{ __('Edit') }}" style="margin-left: 0.5rem; font-size: 0.9285rem; font-weight: 400;">
                                                <i class="write icon"></i><span>{{ __('Edit') }}</span>
                                            </a>
                                        </sup>
                                    @endif
                                </h1>

                            @elseif($user && $user->id == $group->user_id)
                                <p>
                                    <a href="{{ url('home/classes', [$group->id, 'overview']) }}" title="{{ __('Add your class title') }}">
                                        <i class="write icon"></i><span>{{ __('Add your class title') }}</span>
                                    </a>
                                </p>
                            @endif

                            @if($group->uvp)
                                <h2 class="sub header" style="margin-top: 0.5rem;">{{ $group->uvp }}</h2>


                            @elseif($user && $user->id == $group->user_id)
                                <h2 class="sub header" style="margin-top: 0.5rem;">
                                    <a href="{{ url('home/classes', [$group->id, 'overview']) }}" title="{{ __('Add unique value preposition to your class') }}">
                                        <i class="write icon"></i><span>{{ __('Add unique value preposition to your class') }}</span>
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
                                    {{ __('About the class') }}

                                    @if($user && $user->id == $group->user_id)
                                        <a href="{{ url('home/classes', [$group->id, 'overview']) }}" title="{{ __('Edit') }}" style="margin-left: 1rem; font-weight: 400;">
                                            <i class="write icon"></i><span>{{ __('Edit') }}</span>
                                        </a>
                                    @endif
                                </label>

                                <div id="description" class="collapsed">
                                    <p><?php echo nl2br($group->description, false); ?></p>
                                </div>
                            </div><br />

                        @elseif($user && $user->id == $group->user_id)
                            <div>
                                <a href="{{ url('home/classes', [$group->id, 'overview']) }}" title="{{ __('Add your class description') }}">
                                    <i class="write icon"></i><span>{{ __('Add your class description') }}</span>
                                </a>
                            </div><br /><br />
                        @endif

                        <div class="field">
                            <label>
                                {{ __('Class location') }}

                                @if($user && $user->id == $group->user_id)
                                    <a href="{{ url('home/classes', [$group->id, 'location', $location->id]) }}" title="{{ __('Edit') }}" style="margin-left: 1rem; font-weight: 400;">
                                        <i class="write icon"></i><span>{{ __('Edit') }}</span>
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
                                    {{ __('Location is not complete, :link.', [ 'link' => '<a href="' . url('home/classes', [$group->id, 'location', $location->id]) . '" title="' . ucfirst(__('fix the address')) . '" style="margin-left: 0.5rem;"><i class="write icon"></i>' . __('fix the address') . '</a>' ]) }}
                                </p>
                            @endif

                            @if($location->latlng)
                                <div id="map" data-is-full="{{ $location->is_full }}" style="margin: 1rem 0 3rem; min-height: 14rem; position: relative;">
                                    <div id="map-image" style="min-height: 10rem;"></div>

                                    @if($location->is_full)
                                        <div style="text-align: right; padding-right: 0.5rem;"><em>{{ __('Click the map to expand/zoom') }}</em></div>
                                    @endif

                                    @if($location->how_to_find)
                                        <i class="icon circular blue info circle" data-content="{{ $location->how_to_find }}" data-position="left center" data-variation="wide" style="position: absolute; top: 1rem; right: 1rem;"></i>
                                    @endif
                                </div>

                            @elseif($user && $user->id === $group->user_id && $location->is_full)
                                <p style="padding-top: 1rem;">
                                    <a href="{{ url('home/classes', [$group->id, 'location', $location->id, 'map']) }}" title="{{ __('Review your location on the map') }}"><i class="write icon"></i>{{ __('Review your location on the map') }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="five wide column">
                        <div class="field">
                            <label>
                                {{ __('Attendance fees') }}

                                @if($user && $user->id == $group->user_id && count($pricing))
                                    <a href="{{ url('home/classes', [$group->id, 'pricing']) }}" title="{{ __('Edit') }}" style="margin-left: 1rem; font-weight: 400;">
                                        <i class="write icon"></i><span>{{ __('Edit') }}</span>
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
                                </tbody>
                            </table>

                        @else
                            @if($user && $user->id == $group->user_id)
                                <p>
                                    <a href="{{ url('home/classes', [$group->id, 'pricing']) }}" title="{{ __('Add fees') }}">
                                        <i class="write icon"></i>{{ __('Add fees') }}
                                    </a>
                                </p>
                            @else
                                <p>{{ __('There are no fees yet available.') }}</p>
                            @endif
                        @endif

                        @if($group->is_public)
                            <div style="margin-top: 4rem;">
                                <div class="ui divider"></div>
                                <div class="field">
                                    <label style="display: block;">
                                        {{ __('The Coach') }}

                                        @if($user && $user->id === $group->user_id)
                                            <a href="{{ url('home/settings') }}" title="{{ __('Edit') }}" style="float: right; margin-right: 0.5rem; font-weight: 400;">
                                                <i class="write icon"></i>{{ __('Edit') }}
                                            </a>
                                        @endif
                                    </label>
                                </div>
                                <div style="margin-top: 1rem; text-align: center;">
                                    <img class="ui image centered circular tiny" src="{{ $group->owner->avatar_src }}" />
                                    <p style="margin-top: 0.5rem;">{{ $group->owner->fullname }}</p>
                                    <p><strong>{{ __('Ready to get started?') }}</strong> <br />{{ __('Let\'s get in touch.') }}</p>
                                    <button class="ui labeled icon button red" data-toggle="contact-modal">
                                        <i class="comment icon"></i>
                                        {{ __('Contact the Coach') }}
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
                    {{ __('Classes Schedule') }}

                    @if($user && $user->id == $group->user_id && count($weekly_schedule))
                        <a href="{{ url('home/classes', [$group->id, 'schedule', $location->id]) }}" title="{{ __('Edit') }}" style="margin-left: 1rem; font-weight: 400;">
                            <i class="write icon"></i><span>{{ __('Edit') }}</span>
                        </a>
                    @endif
                </label>
            </div>

            <p>
                @if($user && $user->id == $group->user_id && !count($weekly_schedule))
                    <a href="{{ url('home/classes', [$group->id, 'schedule', $location->id]) }}" title="{{ __('Add your class schedule') }}">
                        <i class="write icon"></i>{{ __('Add your class schedule') }}
                    </a>

                @elseif(!count($weekly_schedule))
                    {{ __('Schedule is not yet available.') }}

                @endif
            </p>

            <div class="ui grid" style="{{ count($weekly_schedule) ? '' : 'opacity: 0.3;' }}">

                <?php $x = 0; $nextRow = 1; $dayNames = [_n('Monday|Mondays', 2), _n('Tuesday|Tuesdays', 2), _n('Wednesday|Wednesdays', 2), _n('Thursday|Thursdays', 2), _n('Friday|Fridays', 2), _n('Saturday|Saturdays', 2), _n('Sunday|Sundays', 2)] ?>
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
                    {{ __('Photos') }}

                    @if($user && $user->id == $group->user_id && $photos_count)
                        <a href="{{ url('home/classes', [$group->id, 'photos']) }}" title="{{ __('Edit') }}" style="margin-left: 1rem; font-weight: 400;">
                            <i class="write icon"></i><span>{{ __('Edit') }}</span>
                        </a>

                    @elseif($photos_count > 4)
                        &mdash; <a href="#" title="" data-gallery>{{ _n('[1,4] See all photos|[5,*] See all :photos photos', 5, ['photos' => $photos_count]) }}</a>
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
                                <div style="position: absolute; bottom: 14px; left: 30px; right: 30px; padding: 7px 0; border-top: solid 1px rgba(255, 255, 255, 0.5); text-align: center; color: rgba(255, 255, 255, 0.8);"><strong>{{ _n('[1,4] See all photos|[5,*] See all :photos photos', 5, ['photos' => $photos_count]) }}</strong></div>
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
                    <a href="{{ url('home/classes', [$group->id, 'photos']) }}" title="">
                        <i class="write icon"></i>{{ __('Upload photos') }}
                    </a>
                </p>

            @else
                {{ __('No photos available yet') }}

            @endif
        </div>
    </div>

    <div class="ui page stackable doubling grid">
        <div class="wide column ui form">
            <div class="ui divider"></div>
            <div class="field">
                <label>{{ __('Interested?') }}</label>
            </div>
            <p>{{ __('Wish to give it a try? Or just to ask a question?') }} <br />{{ __('Feel free to contact the coach for any details.') }}</p>
            <button class="ui labeled icon button red" data-toggle="contact-modal">
                <i class="comment icon"></i>
                {{ __('Contact the Coach') }}
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