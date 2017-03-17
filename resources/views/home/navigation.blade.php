@if($user->created_at != $user->updated_at || count($user->myGroups))
    <div class="right menu">
        @if(count($user->myGroups))
            <a class="{{ $active == 'classes' ? 'active ': '' }}item" href="{{ url('/home/classes') }}" title="">{{ trans('main_navigation.my_classes') }}</a>

        @elseif (Request::path() != 'home/classes/new')
            <a class="item" href="{{ url('/home/classes/new') }}" title="" style="color: #00B5AD;">
                <i class="icon idea yellow"></i>
                {{ trans('main_navigation.create_class') }}
            </a>
        @endif

        <a class="{{ $active == 'settings' ? 'active ': '' }}item" href="{{ url('/home/settings') }}" title="">{{ trans('main_navigation.account') }}</a>

        <div class="ui dropdown item">
            <i class="dropdown icon" style="margin: 0;"></i>
            <div class="menu">
                {{--<a class="item" href="{{ url('/terms') }}">{{ trans('main_navigation.terms') }}</a>
                <a class="item" href="{{ url('/privacy-policy') }}">{{ trans('main_navigation.privacy') }}</a>--}}
                <a class="item" href="{{ url('/logout') }}">{{ trans('main_navigation.logout') }}</a>
            </div>
        </div>
    </div>
@endif