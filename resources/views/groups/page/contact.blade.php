<div id="contact-modal" class="ui modal small">
    <div class="header">{{ trans('group/profile.contact_coach') }}</div>
    <form class="ui content form" action="" method="post">
        <input type="hidden" name="location_id" value="{{ $location->id }}" />

        <div class="ui threaded comments" style="margin-top: 0;">
            <div class="comment">
                <span class="avatar">
                    <img src="{{ $group->owner->avatar_src }}">
                </span>
                <div class="content">
                    <span class="author">{{ $group->owner->fullname }}</span>
                    <div class="metadata">
                        {{ $group->phone }}

                        @if($user && $user->id == $group->user_id)
                            <a href="{{ url('/home/classes', [$group->id, 'contact']) }}" title="" style="margin-left: 0.5rem; font-weight: 400;">
                                <i class="write icon"></i><span>{{ trans('buttons.edit') }}</span>
                            </a>
                        @endif
                    </div>
                    <div class="text">{{ trans('group/profile.coach_at', ['title' => $group->title]) }}</div>
                </div>
                <div class="comments" style="padding-top: 1rem; display: none;"></div>
            </div>
        </div>

        <div class="ui divider"></div>

        <div class="ui error message">
            <div class="content">
                <ul class="list"></ul>
            </div>
        </div>

        <div id="person-fields" style="padding-bottom: 1rem;">
            <div class="field required">
                <label>{{ trans('form.your_name') }}</label>
                <div class="ui input">
                    <input name="name" type="text">
                </div>
            </div>

            <div class="field">
                <label>{{ trans('form.phone_number') }}</label>
                <div class="ui icon input">
                    <input name="phone" type="text">
                    <i class="text telephone icon"></i>
                </div>
            </div>

            <div class="field">
                <label>{{ trans('form.email_address') }}</label>
                <div class="ui icon input">
                    <input name="email" type="email">
                    <i class="at icon"></i>
                </div>
            </div>
        </div>

        <div id="message-field" class="field" style="display: none;">
            <label>{{ trans('form.message') }}</label>
            <textarea name="message" style="height: 100px; min-height: 100px;"></textarea>
        </div>
        
        <div class="ui header center aligned" style="margin: 0;">
            <p class="sub header" style="margin-bottom: 1rem;">{{ trans('group/profile.notify_coach_info') }}</p>
            <div class="ui buttons">
                <button class="ui large button" style="display: none;" data-action="close">{{ trans('buttons.close') }}</button>
                <button class="ui large button red" data-label-default="{{ trans('group/profile.notify_enroll') }}" data-label-loading="{{ trans('group/profile.notifying') }}" data-action="notify">{{ trans('group/profile.notify_enroll') }}</button>
                <div class="or"></div>
                <button class="ui large button" data-content="{{ trans('group/profile.additional_message_field_appear') }}" data-position="top right" data-action="mail">
                    <i class="mail outline icon" style="margin-right: 0;"></i>
                </button>
            </div>
        </div>

        <div class="ui header center aligned" style="margin: 0; display: none;">
            <p class="sub header" style="margin-bottom: 1rem;">{{ trans('group/profile.message_willbe_sent_coach') }}</p>
            <div class="ui buttons">
                <button class="ui large button" style="display: none;" data-action="close">{{ trans('buttons.close') }}</button>
                <button class="ui large button red" data-label-default="{{ trans('group/profile.send_message') }}" data-label-loading="{{ trans('group/profile.sending') }}" data-action="send">
                    <i class="send icon"></i>
                    <span>{{ trans('group/profile.send_message') }}</span>
                </button>
            </div>
        </div>
    </form>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</div>