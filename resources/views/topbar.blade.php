<div class="ui borderless menu" style="box-shadow: 0 0 1px rgba(39, 41, 43, 0.15); margin-bottom: 0;">
    <div class="ui page stackable doubling grid" style="margin: 0;">
        <a class="item topbar-logo" href="{{ url('/') }}" title="{{ config('app.name') }}">
            <strong>{{ config('app.name') }}</strong>
        </a>

        @if($user)
            @include('home.navigation', [
                'user' => $user,
                'active' => $activePage ?? null
            ])
        @endif
    </div>
</div>