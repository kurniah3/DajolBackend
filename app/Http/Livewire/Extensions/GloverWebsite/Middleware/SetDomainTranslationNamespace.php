<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Middleware;

use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;

class SetDomainTranslationNamespace
{
    public function handle($request, Closure $next)
    {
        $domain = $request->getHost();
        $websiteDomain = env('APP_WEBSITE_DOMAIN');
        $websiteDomain = str_replace(['https://', 'http://'], '', $websiteDomain);
        if ($domain != $websiteDomain) {
            return $next($request);
        }

        $locale = App::getLocale();
        // $translationsPath = resource_path("lang/website");
        $translationsPath = base_path('app/Http/Livewire/Extensions/GloverWebsite/lang');

        if (File::exists($translationsPath)) {
            App::singleton('translator', function ($app) use ($translationsPath) {
                return new \Illuminate\Translation\Translator(
                    new \Illuminate\Translation\FileLoader($app['files'], $translationsPath),
                    $app['config']['app.locale']
                );
            });
        }

        return $next($request);
    }
}
