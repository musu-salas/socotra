<div id="menu" class="ui vertical fluid menu">
    <a class="{{ $active == 'location' ? 'active' : '' }} item" href="{{ url('home/classes', [$group->id]) }}/location">
        <i class="icon {{ $menu->location ? 'checkmark green' : 'plus red' }}"></i> {{ __('Location') }}
    </a>
    <a class="{{ $active == 'contact' ? 'active' : '' }} item" href="{{ url('home/classes', [$group->id]) }}/contact">
        <i class="icon {{ $menu->contact ? 'checkmark green' : 'plus grey' }}" style="{{ $menu->contact ? '' : 'opacity: 0.15;' }}"></i> {{ __('Contact info') }}
    </a>
    <a class="{{ $active == 'pricing' ? 'active' : '' }} item" href="{{ url('home/classes', [$group->id]) }}/pricing">
        <i class="icon {{ $menu->pricing ? 'checkmark green' : 'plus red' }}"></i> {{ __('Pricing') }}
    </a>
    <a class="{{ $active == 'overview' ? 'active' : '' }} item" href="{{ url('home/classes', [$group->id]) }}/overview">
        <i class="icon {{ $menu->overview ? 'checkmark green' : 'plus red' }}"></i> {{ __('Overview') }}
    </a>
    <a class="{{ $active == 'photos' ? 'active' : '' }} item" href="{{ url('home/classes', [$group->id]) }}/photos">
        <i class="icon {{ $menu->photos ? 'checkmark green' : 'plus red' }}"></i> {{ __('Photos') }}
    </a>
    <a class="{{ $active == 'schedule' ? 'active' : '' }} item" href="{{ url('home/classes', [$group->id]) }}/schedule">
        <i class="icon {{ $menu->schedule ? 'checkmark green' : 'plus grey' }}" style="{{ $menu->schedule ? '' : 'opacity: 0.15;' }}"></i> {{ __('Schedule') }}
    </a>
</div>


<div id="menu-status" class="ui segment basic" style="padding-top: 0; padding-bottom: 0;">
    <span><!--{!! __('Complete :steps<br>to turn your class public', [
        'steps' => '<strong style="color: rgba(201, 58, 102, 1);" data-label="' . _n('step|steps', 1) . '" data-label-plural="' . _n('step|steps', 2) . '">%s</strong>'
    ]) !!}}<br><br><a href="{{ url('classes', [$group->id]) }}" title="">&mdash; {{ __('Preview your class') }}</a>--></span>
    <span><!--<strong>{{ __('Your class is public') }}<em>!</em></strong><br /><br /><a href="{{ url('classes', [$group->id]) }}" title="">&mdash; {{ __('See it here') }}</a>--></span>

    <p style="padding-top: 0; padding-bottom: 0;">
        @if($menu->steps_to_complete > 0)
            {!! __('Complete :steps<br>to turn your class public', [
                'steps' => '<strong style="color: rgba(201, 58, 102, 1);">' . _n('1 step|:steps steps', $menu->steps_to_complete, ['steps' => $menu->steps_to_complete]) . '</strong>'
            ]) !!}
            <br /><br /><a href="{{ url('classes', [$group->id]) }}" title="">&mdash; {{ __('Preview your class') }}</a>
        @else
            <strong>{{ __('Your class is public') }}<em>!</em></strong>
            <br /><br /><a href="{{ url('classes', [$group->id]) }}" title="">&mdash; {{ __('See it here') }}</a>
        @endif
    </p>
</div>