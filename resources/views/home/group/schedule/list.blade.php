@extends('app')

@section('title', trans('group/schedule.page_titles.list'))

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
            'active' => 'schedule'
        ])
    </div>
    <div class="ui eight wide column" style="padding-top: 1.7rem !important;">
        <table class="ui compact unstackable table basic" style="border: 0;">
            <tbody>
                <tr>
                    <td class="collapsing" style="padding: 0;">
                        <h3 class="ui header" style="position: relative; top: -0.6rem; padding-right: 3rem;">{{ trans('group/schedule.classes_schedule') }}</h3>
                    </td>
                    <td style="padding: 0;">
                        <div class="ui selection icon fluid dropdown" data-scoped="location" style="position: relative; top: -0.7rem; white-space: nowrap; background: none;">
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
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="schedule-tables">
            <table class="ui compact unstackable table">
                <thead><tr><th colspan="3">{{ trans_choice('date.monday', 2) }}</th></tr></thead>
                <tbody>
                    @foreach ($group->schedule()->where('location_id', $location->id)->orderBy('starts')->get() as $item)
                        @if ($item->week_day == 0)
                            <tr data-id="{{ $item->id }}" @if($item->description)data-content="{{ $item->description }}" data-variation="wide" data-position="top center"@endif>
                                <td>{{ $item->title }}</td>
                                <td class="right aligned collapsing"><time>{{ $item->starts }}</time> &mdash; <time>{{ $item->ends }}</time></td>
                                <td class="right aligned collapsing">
                                    <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, $item->id]) }}" title="">
                                        <i class="icon write"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="3">
                            <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, 'new']) . '?week_day=0' }}" title="">
                                <i class="icon plus"></i> {{ trans('group/schedule.add_event') }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="ui horizontal divider"></div>

            <table class="ui compact unstackable table">
                <thead><tr><th colspan="3">{{ trans_choice('date.tuesday', 2) }}</th></tr></thead>
                <tbody>
                    @foreach ($group->schedule()->where('location_id', $location->id)->orderBy('starts')->get() as $item)
                        @if ($item->week_day == 1)
                            <tr data-id="{{ $item->id }}" @if($item->description)data-content="{{ $item->description }}" data-variation="wide" data-position="top center"@endif>
                                <td>{{ $item->title }}</td>
                                <td class="right aligned collapsing"><time>{{ $item->starts }}</time> &mdash; <time>{{ $item->ends }}</time></td>
                                <td class="right aligned collapsing">
                                    <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, $item->id]) }}" title="">
                                        <i class="icon write"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="3">
                            <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, 'new']) . '?week_day=1' }}" title="">
                                <i class="icon plus"></i> {{ trans('group/schedule.add_event') }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="ui horizontal divider"></div>

            <table class="ui compact unstackable table">
                <thead><tr><th colspan="3">{{ trans_choice('date.wednesday', 2) }}</th></tr></thead>
                <tbody>
                    @foreach ($group->schedule()->where('location_id', $location->id)->orderBy('starts')->get() as $item)
                        @if ($item->week_day == 2)
                            <tr data-id="{{ $item->id }}" @if($item->description)data-content="{{ $item->description }}" data-variation="wide" data-position="top center"@endif>
                                <td>{{ $item->title }}</td>
                                <td class="right aligned collapsing"><time>{{ $item->starts }}</time> &mdash; <time>{{ $item->ends }}</time></td>
                                <td class="right aligned collapsing">
                                    <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, $item->id]) }}" title="">
                                        <i class="icon write"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="3">
                            <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, 'new']) . '?week_day=2' }}" title="">
                                <i class="icon plus"></i> {{ trans('group/schedule.add_event') }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="ui horizontal divider"></div>

            <table class="ui compact unstackable table">
                <thead><tr><th colspan="3">{{ trans_choice('date.thursday', 2) }}</th></tr></thead>
                <tbody>
                    @foreach ($group->schedule()->where('location_id', $location->id)->orderBy('starts')->get() as $item)
                        @if ($item->week_day == 3)
                            <tr data-id="{{ $item->id }}" @if($item->description)data-content="{{ $item->description }}" data-variation="wide" data-position="top center"@endif>
                                <td>{{ $item->title }}</td>
                                <td class="right aligned collapsing"><time>{{ $item->starts }}</time> &mdash; <time>{{ $item->ends }}</time></td>
                                <td class="right aligned collapsing">
                                    <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, $item->id]) }}" title="">
                                        <i class="icon write"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="3">
                            <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, 'new']) . '?week_day=3' }}" title="">
                                <i class="icon plus"></i> {{ trans('group/schedule.add_event') }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="ui horizontal divider"></div>

            <table class="ui compact unstackable table">
                <thead><tr><th colspan="3">{{ trans_choice('date.friday', 2) }}</th></tr></thead>
                <tbody>
                    @foreach ($group->schedule()->where('location_id', $location->id)->orderBy('starts')->get() as $item)
                        @if ($item->week_day == 4)
                            <tr data-id="{{ $item->id }}" @if($item->description)data-content="{{ $item->description }}" data-variation="wide" data-position="top center"@endif>
                                <td>{{ $item->title }}</td>
                                <td class="right aligned collapsing"><time>{{ $item->starts }}</time> &mdash; <time>{{ $item->ends }}</time></td>
                                <td class="right aligned collapsing">
                                    <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, $item->id]) }}" title="">
                                        <i class="icon write"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="3">
                            <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, 'new']) . '?week_day=4' }}" title="">
                                <i class="icon plus"></i> {{ trans('group/schedule.add_event') }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="ui horizontal divider"></div>

            <table class="ui compact unstackable table">
                <thead><tr><th colspan="3">{{ trans_choice('date.saturday', 2) }}</th></tr></thead>
                <tbody>
                    @foreach ($group->schedule()->where('location_id', $location->id)->orderBy('starts')->get() as $item)
                        @if ($item->week_day == 5)
                            <tr data-id="{{ $item->id }}" @if($item->description)data-content="{{ $item->description }}" data-variation="wide" data-position="top center"@endif>
                                <td>{{ $item->title }}</td>
                                <td class="right aligned collapsing"><time>{{ $item->starts }}</time> &mdash; <time>{{ $item->ends }}</time></td>
                                <td class="right aligned collapsing">
                                    <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, $item->id]) }}" title="">
                                        <i class="icon write"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="3">
                            <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, 'new']) . '?week_day=5' }}" title="">
                                <i class="icon plus"></i> {{ trans('group/schedule.add_event') }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="ui horizontal divider"></div>

            <table class="ui compact unstackable table">
                <thead><tr><th colspan="3">{{ trans_choice('date.sunday', 2) }}</th></tr></thead>
                <tbody>
                    @foreach ($group->schedule()->where('location_id', $location->id)->orderBy('starts')->get() as $item)
                        @if ($item->week_day == 6)
                            <tr data-id="{{ $item->id }}" @if($item->description)data-content="{{ $item->description }}" data-variation="wide" data-position="top center"@endif>
                                <td>{{ $item->title }}</td>
                                <td class="right aligned collapsing"><time>{{ $item->starts }}</time> &mdash; <time>{{ $item->ends }}</time></td>
                                <td class="right aligned collapsing">
                                    <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, $item->id]) }}" title="">
                                        <i class="icon write"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    <tr>
                        <td colspan="3">
                            <a href="{{ url('/home/classes', [$group->id, 'schedule', $location->id, 'new']) . '?week_day=6' }}" title="">
                                <i class="icon plus"></i> {{ trans('group/schedule.add_event') }}
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="tips-column" class="five wide column" style="background: #fff;">
        <div class="ui icon message" style="background: none; box-shadow: none;">
            <i class="asterisk icon yellow" style="display: inline-block; margin-right: 0;"></i>
            <div class="content">
                <div class="header">{{ trans('group/schedule.helpers.manage_schedule') }}</div>
                <p>{!! trans('group/schedule.helpers.manage_schedule_description') !!}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.schedule.js') ) !!}
@endsection