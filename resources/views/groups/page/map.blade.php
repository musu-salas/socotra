<div id="map-modal" class="ui modal fullscreen long">
    <div class="content" data-latlng="{{ $location->latlng }}" data-is-full="{{ $location->isFull }}" data-address="{{ $location->full_address }}" data-find="{{ $location->how_to_find  }}" style="position: relative; padding: 0 !important;"></div>
    <div class="actions">
        <p style="float: left; with: 70%; text-align: center;">{{ $location->full_address }}</p>
        <div class="ui button">{{ trans('buttons.close') }}</div>
    </div>
</div>