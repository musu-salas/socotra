@extends('app')

@section('title', trans('group/schedule.page_titles.' . ($schedule ? 'edit' : 'add')))

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
    <div class="eight wide column" style="padding-top: 1.7rem !important;">
        <h3 class="ui header" style="margin-bottom: 0.2rem;">{{ trans('group/schedule.' . ($schedule ? 'edit' : 'add') . '_event') }}</h3>
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
                    {{ trans('form.title') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ trans('form.max_chars', ['chars' => 50]) }}</em>
                </label>
                <div class="ui input">
                    <input id="title" value="{{ $schedule ? $schedule->title : '' }}" name="title" type="text" maxlength="50">
                </div>
            </div>
            <div class="fields">
                <div class="required field">
                    <label>{{ trans('group/schedule.starts_every', ['weekday' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][$week_day]]) }}</label>
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
                    <label>{{ trans('group/schedule.ends_at') }}</label>
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
                    {{ trans('form.short_description') }}
                    <em style="float: right; white-space: nowrap; color: #aaa; font-weight: 400; padding-right: 0.5rem;">{{ trans('form.max_chars', ['chars' => 255]) }}</em>
                </label>
                <textarea id="description" name="description" style="height: 60px; min-height: 60px;" maxlength="255">{{ $schedule ? $schedule->description : '' }}</textarea>
            </div>

            <div class="ui stackable doubling grid">
                <div class="eight wide column">
                    <button class="ui button red" type="submit">{{ trans('buttons.' . ($schedule ? 'update' : 'save')) }}</button>
                </div>
                <div class="eight wide column right aligned">
                    @if ($schedule)
                    <p style="padding-top: 0.78571em;">
                        <a
                            id="delete"
                            href="{{ url('/api/v1/classes', [$group->id, 'schedule', $schedule->id]) }}"
                            title=""
                            data-deleting-label="{{ trans('group/schedule.deleting') }}"
                            data-error-label="{{ trans('group/schedule.problem_deleting') }}"
                        >{{ trans('group/schedule.delete') }}</a>
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
                <div class="header">{{ trans('group/schedule.helpers.edit_schedule') }}</div>
                <p>{{ trans('group/schedule.helpers.edit_schedule_description') }}</p>
            </div>
        </div>
    </div>
</div>

<div id="delete-modal" class="ui small modal">
    <div class="header">{{ trans('group/schedule.really_delete') }}</div>
    <div class="content">
        <p>{{ trans('group/schedule.really_delete_info', ['weekday' => ($schedule ? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][$week_day] : '')]) }}.</p>
    </div>
    <div class="actions">
        <div class="ui basic cancel button">{{ trans('buttons.cancel') }}</div>
        <div class="ui red right labeled icon ok button">
            {{ trans('buttons.delete') }}
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