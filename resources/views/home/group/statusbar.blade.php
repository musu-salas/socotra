
<div id="status-bar" class="ui visible {{ $group->is_public ? 'success' : 'warning' }} message" style="position: fixed; width: 100%; top: 34px; left: 0; border-radius: 0; margin: 0; z-index: 1; padding: 0.75em 0.5em; border-bottom: solid 1px rgba(39, 41, 43, 0.15); box-shadow: none;">
    <span><!--<i class="icon hide"></i>{{ __('Your class is not public') }} &mdash; {{ __('complete') }} <strong style="color: rgba(201, 58, 102, 1);" data-label="{{ substr(_n('1 step|:steps steps', 1), 2) }}" data-label-plural="{{ substr(_n('1 step|:steps steps', 2, ['steps' => 2]), 2) }}">%s</strong>--></span>
    <span><!--<i class="icon check circle"></i>{{ __('It is public') }}<em>!</em> <a href="{{ url('classes', [$group->id]) }}" title="">{{ __('Preview your class') }}</a> {{ __('and share with friends') }}--></span>

    <p style="margin-top: 0;">
        @if($group->is_public)
            <i class="icon check circle"></i>{{ __('It is public') }}<em>!</em> <a href="{{ url('classes', [$group->id]) }}" title="">{{ __('Preview your class') }}</a> {{ __('and share with friends') }}

        @else
            <i class="icon hide"></i>{{ __('Your class is not public') }} &mdash; {{ __('complete') }} <strong style="color: rgba(201, 58, 102, 1);">{{ _n('1 step|:steps steps', $menu->steps_to_complete, ['steps' => $menu->steps_to_complete]) }}</strong>
        @endif
    </p>
</div>