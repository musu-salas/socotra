@if($user->created_at != $user->updated_at || count($user->myGroups))
    <div class="right menu" style="padding: 0;">
        @if(count($user->myGroups))
            <a class="{{ $active == 'classes' ? 'active ': '' }}item" href="{{ url('home/classes') }}" title="{{ __('My Classes') }}">{{ __('My Classes') }}</a>

        @elseif (Request::path() != 'home/classes/new')
            <a class="item" href="{{ url('home/classes/new') }}" title="{{ __('Create your class') }}" style="color: #00B5AD;">
                <i class="icon idea yellow"></i>
                {{ __('Create your class') }}
            </a>
        @endif

        <a class="{{ $active == 'settings' ? 'active ': '' }}item" href="{{ url('home/settings') }}" title="{{ __('Account') }}">{{ __('Account') }}</a>

        <div class="ui dropdown item">
            <i class="dropdown icon" style="margin: 0;"></i>
            <div class="menu">
                {{--<a class="item" href="{{ url('terms') }}">{{ __('Terms of service') }}</a>
                <a class="item" href="{{ url('privacy-policy') }}">{{ __('Privacy policy') }}</a>--}}
                <a class="item" href="{{ url('logout') }}">{{ __('Logout') }}</a>
            </div>
        </div>
    </div>
@endif