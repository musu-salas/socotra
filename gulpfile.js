var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.copy(
        'resources/assets/vendor/semantic-ui/dist/semantic.css',
        'public/semantic.css'

    ).copy(
        'resources/assets/vendor/semantic-ui/dist/semantic.js',
        'public/semantic.js'

    ).copy(
        'resources/assets/vendor/semantic-ui/dist/themes/default',
        'public/themes/default'
    )
});
