<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Validator;
use App\Http\Validators\HashValidator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      if (App::environment('production')) {
          URL::forceScheme('https');
      }

      Validator::resolver(function($translator, $data, $rules, $messages) {
        return new HashValidator($translator, $data, $rules, $messages);
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		  $this->app->bind(
        'Illuminate\Contracts\Auth\Registrar',
        'App\Services\Registrar'
		  );
    }
}
