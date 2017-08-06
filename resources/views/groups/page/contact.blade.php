<div id="contact-modal" class="ui modal small">
    <div class="header">{{ __('Contact the Coach') }}</div>
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
                            <a href="{{ url('home/classes', [$group->id, 'contact']) }}" title="{{ __('Edit contact information') }}" style="margin-left: 0.5rem; font-weight: 400;">
                                <i class="write icon"></i><span>{{ __('Edit') }}</span>
                            </a>
                        @endif
                    </div>
                    <div class="text">{{ __('The coach at :title', ['title' => $group->title]) }}</div>
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
                <label>{{ __('Your name') }}</label>
                <div class="ui input">
                    <input name="name" type="text">
                </div>
            </div>

            <div class="field">
                <label>{{ __('Phone number') }}</label>
                <div class="ui icon input">
                    <input name="phone" type="text">
                    <i class="text telephone icon"></i>
                </div>
            </div>

            <div class="field">
                <label>{{ __('Email address') }}</label>
                <div class="ui icon input">
                    <input name="email" type="email">
                    <i class="at icon"></i>
                </div>
            </div>
        </div>

        <div id="message-field" class="field" style="display: none;">
            <label>{{ __('Message') }}</label>
            <textarea name="message" style="height: 100px; min-height: 100px;"></textarea>
        </div>

        <div class="ui header center aligned" style="margin: 0;">
            <p class="sub header" style="margin-bottom: 1rem;">{{ __('Notify coach you wish to enroll to the class, so the coach contacts you back shortly. Or just send a direct message.') }}</p>
            <div class="ui buttons">
                <button class="ui large button" style="display: none;" data-action="close">{{ __('Close') }}</button>
                <button class="ui large button red" data-label-default="{{ __('Notify to enroll') }}" data-label-loading="{{ __('Notifying...') }}" data-action="notify">{{ __('Notify to enroll') }}</button>
                <div class="or"></div>
                <button class="ui large button" data-content="{{ __('Additional message field will appear') }}" data-position="top right" data-action="mail">
                    <i class="mail outline icon" style="margin-right: 0;"></i>
                </button>
            </div>
        </div>

        <div class="ui header center aligned" style="margin: 0; display: none;">
            <p class="sub header" style="margin-bottom: 1rem;">{{ __('Your message will be sent directly to the coach.') }}</p>
            <div class="ui buttons">
                <button class="ui large button" style="display: none;" data-action="close">{{ __('Close') }}</button>
                <button class="ui large button red" data-label-default="{{ __('Send the message') }}" data-label-loading="{{ __('Sending...') }}" data-action="send">
                    <i class="send icon"></i>
                    <span>{{ __('Send the message') }}</span>
                </button>
            </div>
        </div>
    </form>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</div>