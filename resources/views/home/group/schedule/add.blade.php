@extends('app')

@if($schedule)
    @section('title', __('Edit schedule event') . ' · ' . config('app.name'))

@else
    @section('title', __('Add schedule event') . ' · ' . config('app.name'))
@endif

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
            'active' => 'schedule'
        ])
    </div>
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header" style="margin-bottom: 0.2rem;">
            @if($schedule)
                {{ __('Edit schedule event') }}

            @else
                {{ __('Add schedule event') }}
            @endif
        </h3>
        <p style="margin: 0 0 1.5rem 1rem;">&rarr; {{ $location->full_address }}</p>

        <form class="ui form" action="" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="week_day" value="{{ $week_day }}">

            <div class="ui error message {{ $errors->count() ? 'visible' : '' }}">
                <div class="content">
                    <ul class="list">
                        @if ($errors->count())
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="required field">
                <label for="title">
                    {{ __('Title') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ __('Max :chars chars', ['chars' => 50]) }}</em>
                </label>
                <div class="ui input">
                    <input id="title" value="{{ $schedule ? $schedule->title : '' }}" name="title" type="text" maxlength="50">
                </div>
            </div>
            <div class="fields">
                <div class="required field">
                    <label>{{ __('Starts every :weekday at', ['weekday' => [__('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday'), __('Sunday')][$week_day]]) }}</label>
                    <div class="ui icon input">
                        <div class="ui selection dropdown" data-scoped="time" style="position: absolute; top: 0; left: 0; min-width: 100%;">
                            <i class="dropdown icon"></i>
                            <div class="default text" style="visibility: hidden;"></div>
                            <div class="menu">
                                <div class="item">00:00</div>
                                <div class="item">00:30</div>
                                <div class="item">01:00</div>
                                <div class="item">01:30</div>
                                <div class="item">02:00</div>
                                <div class="item">02:30</div>
                                <div class="item">03:00</div>
                                <div class="item">03:30</div>
                                <div class="item">04:00</div>
                                <div class="item">04:30</div>
                                <div class="item">05:00</div>
                                <div class="item">05:30</div>
                                <div class="item">06:00</div>
                                <div class="item">06:30</div>
                                <div class="item">07:00</div>
                                <div class="item">07:30</div>
                                <div class="item">08:00</div>
                                <div class="item">08:30</div>
                                <div class="item">09:00</div>
                                <div class="item">09:30</div>
                                <div class="item">10:00</div>
                                <div class="item">10:30</div>
                                <div class="item">11:00</div>
                                <div class="item">11:30</div>
                                <div class="item">12:00</div>
                                <div class="item">12:30</div>
                                <div class="item">13:00</div>
                                <div class="item">13:30</div>
                                <div class="item">14:00</div>
                                <div class="item">14:30</div>
                                <div class="item">15:00</div>
                                <div class="item">15:30</div>
                                <div class="item">16:00</div>
                                <div class="item">16:30</div>
                                <div class="item">17:00</div>
                                <div class="item">17:30</div>
                                <div class="item">18:00</div>
                                <div class="item">18:30</div>
                                <div class="item">19:00</div>
                                <div class="item">19:30</div>
                                <div class="item">20:00</div>
                                <div class="item">20:30</div>
                                <div class="item">21:00</div>
                                <div class="item">21:30</div>
                                <div class="item">22:00</div>
                                <div class="item">22:30</div>
                                <div class="item">23:00</div>
                                <div class="item">23:30</div>
                            </div>
                        </div>
                        <input name="starts" type="text" value="{{ $schedule ? $schedule->starts : '' }}" style="position: relative; z-index: 11;" maxlength="5">
                        <i class="dropdown icon" style="z-index: 11;"></i>
                    </div>
                </div>
                <div class="required field">
                    <label>{{ __('Ends at') }}</label>
                    <div class="ui icon input">
                        <div class="ui selection dropdown" data-scoped="time" style="position: absolute; top: 0; left: 0;min-width: 100%;">
                            <i class="dropdown icon"></i>
                            <div class="default text" style="visibility: hidden;"></div>
                            <div class="menu">
                                <div class="item">00:00</div>
                                <div class="item">00:30</div>
                                <div class="item">01:00</div>
                                <div class="item">01:30</div>
                                <div class="item">02:00</div>
                                <div class="item">02:30</div>
                                <div class="item">03:00</div>
                                <div class="item">03:30</div>
                                <div class="item">04:00</div>
                                <div class="item">04:30</div>
                                <div class="item">05:00</div>
                                <div class="item">05:30</div>
                                <div class="item">06:00</div>
                                <div class="item">06:30</div>
                                <div class="item">07:00</div>
                                <div class="item">07:30</div>
                                <div class="item">08:00</div>
                                <div class="item">08:30</div>
                                <div class="item">09:00</div>
                                <div class="item">09:30</div>
                                <div class="item">10:00</div>
                                <div class="item">10:30</div>
                                <div class="item">11:00</div>
                                <div class="item">11:30</div>
                                <div class="item">12:00</div>
                                <div class="item">12:30</div>
                                <div class="item">13:00</div>
                                <div class="item">13:30</div>
                                <div class="item">14:00</div>
                                <div class="item">14:30</div>
                                <div class="item">15:00</div>
                                <div class="item">15:30</div>
                                <div class="item">16:00</div>
                                <div class="item">16:30</div>
                                <div class="item">17:00</div>
                                <div class="item">17:30</div>
                                <div class="item">18:00</div>
                                <div class="item">18:30</div>
                                <div class="item">19:00</div>
                                <div class="item">19:30</div>
                                <div class="item">20:00</div>
                                <div class="item">20:30</div>
                                <div class="item">21:00</div>
                                <div class="item">21:30</div>
                                <div class="item">22:00</div>
                                <div class="item">22:30</div>
                                <div class="item">23:00</div>
                                <div class="item">23:30</div>
                            </div>
                        </div>
                        <input name="ends" type="text" value="{{ $schedule ? $schedule->ends : '' }}" style="position: relative; z-index: 11;" maxlength="5">
                        <i class="dropdown icon" style="z-index: 11;"></i>
                    </div>
                </div>
            </div>
            <div class="field">
                <label for="description">
                    {{ __('Short description') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ __('Max :chars chars', ['chars' => 255]) }}</em>
                </label>
                <textarea id="description" name="description" style="height: 60px; min-height: 60px;" maxlength="255">{{ $schedule ? $schedule->description : '' }}</textarea>
            </div>

            <div class="ui stackable doubling grid">
                <div class="eight wide column">
                    <button class="ui button red" type="submit">
                        @if($schedule)
                            {{ __('Update') }}
                        @else
                            {{ __('Save') }}

                        @endif
                    </button>
                </div>
                <div class="eight wide column right aligned">
                    @if ($schedule)
                    <p style="padding-top: 0.78571em;">
                        <a
                            id="delete"
                            href="{{ url('api/v1/classes', [$group->id, 'schedule', $schedule->id]) }}"
                            title=""
                            data-deleting-label="{{ __('Deleting...') }}"
                            data-error-label="{{ __('There was a problem deleting this event.') }}"
                        >{{ __('Delete this event?') }}</a>
                    </p>
                    @endif
                </div>
            </div>
        </form>

    </div>
    <div id="tips-column" class="five wide column" style="background: #fff;">
        <div class="ui icon message" style="background: none; box-shadow: none;">
            <i class="asterisk icon yellow" style="display: inline-block; margin-right: 0;"></i>
            <div class="content">
                <div class="header">{{ __('Adding/editing event') }}</div>
                <p>{{ __('Specify event title and short description, so people clearly understand type of the class to attend.') }}</p>
            </div>
        </div>
    </div>
</div>

<div id="delete-modal" class="ui small modal">
    <div class="header">{{ __('Do you really wish to delete this event?') }}</div>
    <div class="content">
        <p>{{ __('Session will be removed from :weekday', ['weekday' => ($schedule ? [__('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday'), __('Sunday')][$week_day] : '')]) }}.</p>
    </div>
    <div class="actions">
        <div class="ui basic cancel button">{{ __('Cancel') }}</div>
        <div class="ui red right labeled icon ok button">
            {{ __('Delete') }}
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>

<input type="hidden" name="group_id" value="{{ $group->id }}" />
<input type="hidden" name="location_id" value="{{ $location->id }}" />
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.js') ) !!}
{!! HTML::script( asset('socotra.group-manage.schedule.js') ) !!}
@endsection