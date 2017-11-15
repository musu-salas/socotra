<footer style="margin-top: 3rem;">
  <hr />
  <div class="ui page stackable doubling grid" style="margin: 0.5rem 0 1rem;">
    <div class="four wide column">
      <p>{{ __('Change country/language') }}</p>

      <select id="country-switcher">
        @foreach (config('countries') as $country => $defaults)
          @if ($country === config('app.country'))
            <option value="{{ $country }}" selected>{{ $defaults['label'] }}</option>
          @else
            <option value="{{ $country }}">{{ $defaults['label'] }}</option>
          @endif
        @endforeach
      </select>

      <select id="locale-switcher" data-current="{{ config('app.locale') }}" data-default="{{ config('countries')[config('app.country')]['defaultLocale'] }}">
        @foreach (config('locales') as $locale)
          @if ($locale === config('app.locale'))
            <option value="{{ $locale }}" selected>{{ $locale }}</option>
          @else
            <option value="{{ $locale }}">{{ $locale }}</option>
          @endif
        @endforeach
      </select>

    </div>
    <div class="twelve wide column"></div>
  </div>
</footer>