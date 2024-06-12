<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        //only set locale if the domain match the website settings
        $websiteDomain = env('APP_WEBSITE_DOMAIN', config('app.url'));
        $websiteDomain = str_replace("https://", "", $websiteDomain);
        $websiteDomain = str_replace("http://", "", $websiteDomain);
        $websiteDomain = str_replace("www.", "", $websiteDomain);
        //
        $currentDomain = request()->getHost();
        $currentDomain = str_replace("www.", "", $currentDomain);
        //
        if ($websiteDomain != $currentDomain) {
            return $next($request);
        }

        //check the domain match the website settings
        $locale = Session::get('locale', env('WEBSITE_DEFAULT_LANGUAGE', config('app.locale')));
        // App::setLocale($locale);
        //just set locale for this request only
        app()->setLocale($locale);
        return $next($request);
    }
}
