<div id="menu" class="ui vertical fluid menu">
    <a class="{{ $active == 'location' ? 'active' : '' }} item" href="{{ url('/home/classes', [$group->id]) }}/location">
        <i class="icon {{ $menu->location ? 'checkmark green' : 'plus red' }}"></i> {{ trans('group/menu.pages.location') }}
    </a>
    <a class="{{ $active == 'contact' ? 'active' : '' }} item" href="{{ url('/home/classes', [$group->id]) }}/contact">
        <i class="icon {{ $menu->contact ? 'checkmark green' : 'plus grey' }}" style="{{ $menu->contact ? '' : 'opacity: 0.15;' }}"></i> {{ trans('group/menu.pages.contact_info') }}
    </a>
    <a class="{{ $active == 'pricing' ? 'active' : '' }} item" href="{{ url('/home/classes', [$group->id]) }}/pricing">
        <i class="icon {{ $menu->pricing ? 'checkmark green' : 'plus red' }}"></i> {{ trans('group/menu.pages.pricing') }}
    </a>
    <a class="{{ $active == 'overview' ? 'active' : '' }} item" href="{{ url('/home/classes', [$group->id]) }}/overview">
        <i class="icon {{ $menu->overview ? 'checkmark green' : 'plus red' }}"></i> {{ trans('group/menu.pages.overview') }}
    </a>
    <a class="{{ $active == 'photos' ? 'active' : '' }} item" href="{{ url('/home/classes', [$group->id]) }}/photos">
        <i class="icon {{ $menu->photos ? 'checkmark green' : 'plus red' }}"></i> {{ trans('group/menu.pages.photos') }}
    </a>
    <a class="{{ $active == 'schedule' ? 'active' : '' }} item" href="{{ url('/home/classes', [$group->id]) }}/schedule">
        <i class="icon {{ $menu->schedule ? 'checkmark green' : 'plus grey' }}" style="{{ $menu->schedule ? '' : 'opacity: 0.15;' }}"></i> {{ trans('group/menu.pages.schedule') }}
    </a>
</div>


<div id="menu-status" class="ui segment basic" style="padding-top: 0; padding-bottom: 0;">
    <span><!--{{ trans('group/menu.complete') }} <strong style="color: rgba(201, 58, 102, 1);" data-label="{{ substr(trans_choice('group/status_bar.steps', 1), 2) }}" data-label-plural="{{ substr(trans_choice('group/status_bar.steps', 2, ['steps' => 2]), 2) }}">%s</strong><br />{{ trans('group/menu.turn_public') }}<br /><br /><a href="{{ url('/classes', [$group->id]) }}" title="">&mdash; {{ trans('group/status_bar.preview_class') }}</a>--></span>
    <span><!--<strong>{{ trans('group/menu.is_public') }}<em>!</em></strong><br /><br /><a href="{{ url('/classes', [$group->id]) }}" title="">&mdash; {{ trans('group/menu.see_it') }}</a>--></span>

    <p style="padding-top: 0; padding-bottom: 0;">
        @if($menu->steps_to_complete > 0)
            {{ trans('group/menu.complete') }} <strong style="color: rgba(201, 58, 102, 1);">{{ trans_choice('group/status_bar.steps', $menu->steps_to_complete, ['steps' => $menu->steps_to_complete]) }}</strong>
            <br />{{ trans('group/menu.turn_public') }}
            <br /><br /><a href="{{ url('/classes', [$group->id]) }}" title="">&mdash; {{ trans('group/status_bar.preview_class') }}</a>
        @else
            <strong>{{ trans('group/menu.is_public') }}<em>!</em></strong>
            <br /><br /><a href="{{ url('/classes', [$group->id]) }}" title="">&mdash; {{ trans('group/menu.see_it') }}</a>
        @endif
    </p>
</div>