<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // පද්ධතිය Vercel වැනි සජීවී (Production) මට්ටමක පවතින විට
        // සියලුම ලින්ක් https හරහා බලගැන්වීමට මෙය උපකාරී වේ.
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
