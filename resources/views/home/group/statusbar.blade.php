
<div id="status-bar" class="ui visible {{ $group->is_public ? 'success' : 'warning' }} message" style="position: fixed; width: 100%; top: 34px; left: 0; border-radius: 0; margin: 0; z-index: 1;">
    <span><!--<i class="icon hide"></i>{{ trans('group/status_bar.not_public_class') }} &mdash; {{ trans('group/status_bar.complete') }} <strong style="color: rgba(201, 58, 102, 1);" data-label="{{ substr(trans_choice('group/status_bar.steps', 1), 2) }}" data-label-plural="{{ substr(trans_choice('group/status_bar.steps', 2, ['steps' => 2]), 2) }}">%s</strong>--></span>
    <span><!--<i class="icon check circle"></i>{{ trans('group/status_bar.is_public') }}<em>!</em> <a href="{{ url('/classes', [$group->id]) }}" title="">{{ trans('group/status_bar.preview_class') }}</a> {{ trans('group/status_bar.and_share') }}--></span>

    <p style="margin-top: 0;">
        @if($group->is_public)
            <i class="icon check circle"></i>{{ trans('group/status_bar.is_public') }}<em>!</em> <a href="{{ url('/classes', [$group->id]) }}" title="">{{ trans('group/status_bar.preview_class') }}</a> {{ trans('group/status_bar.and_share') }}

        @else
            <i class="icon hide"></i>{{ trans('group/status_bar.not_public_class') }} &mdash; {{ trans('group/status_bar.complete') }} <strong style="color: rgba(201, 58, 102, 1);">{{ trans_choice('group/status_bar.steps', $menu->steps_to_complete, ['steps' => $menu->steps_to_complete]) }}</strong>
        @endif
    </p>
</div>