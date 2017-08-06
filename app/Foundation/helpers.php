<?php declare(strict_types=1);

use Illuminate\Contracts\Routing\UrlGenerator;

if (! function_exists('_n')) {
    /**
     * Translates the given message based on a count.
     *
     * @param  string  $id
     * @param  int|array|\Countable  $number
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    function _n($id, $number, $replace = [], $locale = null)
    {
        return trans_choice(__($id), $number, $replace, $locale);
    }
}

if (! function_exists('url_locale')) {
    /**
     * Retrieves locale for url injection.
     *
     * @return string
     */
    function url_locale()
    {
        $locale = config('app.locale');
        $countries = config('countries');
        $country = $countries[config('app.country')];
        $defaultLocale = $country['defaultLocale'];

        if ($locale === $defaultLocale) {
            return '';
        }

        return $locale;
    }
}

if (! function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string  $path
     * @param  mixed   $parameters
     * @param  bool    $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function url($path = '/', $parameters = [], $secure = null)
    {
        $url_locale = url_locale();

        if (starts_with($path, 'http') || !$url_locale) {
            return app(UrlGenerator::class)->to($path, $parameters, $secure);
        }

        $segments = array_filter(explode('/', $path));
        $locale = $segments[0] ?? null;

        if (!$locale || strlen($locale) !== 2 || !in_array($locale, config('locales'))) {
            array_unshift($segments, $url_locale);
            $path = implode($segments, '/');
        }

        return app(UrlGenerator::class)->to($path, $parameters, $secure);
    }
}

if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = false)
    {
        if (config('app.debug') && starts_with($name, 'debugbar')) {
            return app('url')->route($name, array_add($parameters, 'country', 'www'), false);
        }

        if ($name === 'password.reset') {
            if (is_string($parameters)) {
                $parameters = array_add([], 'token', $parameters);
            }

            return app('url')->route($name, array_add($parameters, 'country', config('app.country')), false);
        }

        return url(app('url')->route($name, array_add($parameters, 'country', config('app.country')), false));
    }
}