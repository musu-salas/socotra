<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /** @var string */
    const CACHE_KEY = 'translations';

    /** @return string */
    public static function getTranslations()
    {
        $locale = config('app.locale');
        $key = self::getCacheKeyByLocale($locale);

        return Cache::get($key);
    }

    /** @return void */
    private static function cacheByLocale(string $locale)
    {
        $key = self::getCacheKeyByLocale($locale);

        if (App::isLocal()) {
            Cache::forget($key);
        }

        Cache::rememberForever($key, function () use ($locale) {
            return self::getTranslationsByLocale($locale);
        });
    }

    /** @return string */
    private static function getCacheKeyByLocale(string $locale)
    {
        return TranslationServiceProvider::CACHE_KEY . ":$locale";
    }

    /** @return string */
    private static function getTranslationsByLocale(string $locale)
    {
        $filename = resource_path("lang/$locale.json");
        $contents = File::get($filename);

        return self::compressJson($contents);
    }

    /** @return string */
    private static function compressJson(string $json)
    {
        return json_encode(json_decode($json), JSON_UNESCAPED_UNICODE);
    }

    /** @return void */
    public function boot()
    {
        $locales = config('locales');

        array_walk($locales, ['self', 'cacheByLocale']);
    }
}
