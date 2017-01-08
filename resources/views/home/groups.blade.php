@extends('app')

@section('title', trans('user/groups.page_title'))

@section('content')
<div class="ui fixed borderless menu" style="box-shadow: 0 0 1px rgba(39, 41, 43, 0.15);">
    <div class="ui page stackable doubling grid" style="margin: 0;">
        <a class="item" href="{{ url('/') }}" title="{{ config('custom.code') }}">
            <strong>{{ config('custom.code') }}</strong>
        </a>

        @include('home.navigation', [
            'user' => $user,
            'active' => 'classes'
        ])
    </div>
</div>

<br /><br /><br />
<div>
    <div class="ui page stackable doubling grid">
        <div class="wide column">
            <div class="ui stackable doubling grid">
                <div class="one column row">
                    <div class="wide column">
                        <h2 class="ui header">
                            <i class="users icon"></i>
                            <div class="content">
                                {{ trans('user/groups.my_classes') }}
                                <div class="sub header">{{ trans('user/groups.manage_classes') }}</div>
                            </div>
                        </h2>
                        <div class="ui divider"></div>
                    </div>
                </div>

                <div class="ui one column row">
                    <div class="column">
                        <div class="ui segment">
                            <div class="ui top attached large label">&nbsp;</div>
                            <div>
                                <div class="ui relaxed divided list">

                                    @foreach($myGroups as $group)

                                        <div class="item">
                                            <div class="right floated">
                                                @if($group->is_public)
                                                    <a href="{{ url('/classes', [$group->id]) }}">
                                                        <i class="icon check green"></i> {{ trans('buttons.preview') }}
                                                    </a>

                                                @else
                                                    <?php $steps = $group->menu->steps_to_complete; ?>
                                                    <a href="{{ url('/home/classes', [$group->id]) }}">
                                                        <div class="ui button red">{{ trans_choice('user/groups.steps_to_complete', $steps, ['steps' => $steps]) }}</div>
                                                    </a>
                                                @endif
                                            </div>

                                            @if($group->cover_photo)
                                                {!! HTML::image( asset($group->cover_photo->thumbnail_src), '', [
                                                    'class' => 'ui tiny image',
                                                ]) !!}

                                            @else
                                                {!! HTML::image( asset('images/empty.png'), '', [
                                                    'class' => 'ui tiny image',
                                                    'style' => 'background: #cacccd;'
                                                ]) !!}
                                            @endif

                                            <div class="content">
                                                <h3 class="ui header" style="margin-bottom: 0.5rem;">{{ ($group->title) ? $group->title : (($group->creative_field2) ? $group->creative_field2 : $group->creative_field1) }}</h3>

                                                <div class="description">
                                                    <a href="{{ url('/home/classes', [$group->id]) }}" title="" style="color: #009fda !important;">
                                                        <i class="icon write"></i>{{ trans('user/groups.edit_class') }}
                                                    </a>

                                                    @if(isset($steps) && $steps > 0)
                                                        &mdash; {{ trans_choice('user/groups.steps_to_complete', $steps, ['steps' => $steps]) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <?php unset($steps); ?>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        <a href="{{ url('/home/classes/new') }}" class="ui submit labeled icon basic button">
                            <i class="plus icon"></i>
                            {{ trans('user/groups.create_class') }}
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{!! HTML::script( asset('socotra.general.js') ) !!}
{!! HTML::script( asset('socotra.groups-manage.js') ) !!}
@endsection