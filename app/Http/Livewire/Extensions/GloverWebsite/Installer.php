<?php

namespace App\Http\Livewire\Extensions\GloverWebsite;


use GeoSot\EnvEditor\Facades\EnvEditor;
use App\Models\NavMenu;

class Installer
{

    public function run()
    {
        $this->createnavMenu();
        $this->autoCreateENVDomain();
        $this->createExtensionRoutes();
        $this->addLocalMiddleWare();
        // $this->handleTranslations();
    }

    public function createnavMenu()
    {

        $navMenu = NavMenu::where('route', 'glover-website.admin.settings')->first();
        if (empty($navMenu)) {
            \DB::table('nav_menus')->insert(array(
                0 =>
                array(
                    'name' => 'Website Extension Settings',
                    'route' => 'glover-website.admin.settings',
                    'roles' => 'admin',
                    'icon' => 'heroicon-o-cog',
                ),
            ));
        }
    }

    public function autoCreateENVDomain()
    {
        //
        $key = "APP_WEBSITE_DOMAIN";
        $exists = EnvEditor::keyExists($key);
        if (!$exists) {
            //get current domain without www and subdomain
            $domain = request()->getHost();
            $domain = str_replace("www.", "", $domain);
            EnvEditor::addKey($key, $domain);
        }
    }


    //
    public function createExtensionRoutes()
    {


        $extensionRoutePath = base_path('routes/glover-website-route.php');
        //
        $extensionRoutesExist = file_exists($extensionRoutePath);
        //delete the file 
        if ($extensionRoutesExist) {
            unlink($extensionRoutePath);
        }

        //get file content ffrom file in current dir glover-website-route.php
        $replaceContent = file_get_contents(__DIR__ . '/glover-website-route.php');
        $extensionRouteFile = fopen($extensionRoutePath, "w") or die("Unable to open file!");
        fwrite($extensionRouteFile, $replaceContent);
        fclose($extensionRouteFile);


        //adding the extension to routeservice provider
        $routeServiceProviderPath = app_path('Providers/RouteServiceProvider.php');
        $routeFound = $this->fileContains("glover-website-route", $routeServiceProviderPath);
        if (!$routeFound) {
            $replaceContent = <<<'EOD'
               //glover-website-route
               Route::middleware('web')
                   ->namespace($this->namespace)
                   ->group(base_path('routes/glover-website-route.php'));

                Route::middleware('web')
   
   
               EOD;
            //
            $contentSearched = "Route::middleware('web')";
            $this->replaceContent($contentSearched, $replaceContent, $routeServiceProviderPath);
        }

        //

    }

    public function addLocalMiddleWare()
    {

        $appHttpKernelPath = app_path('Http/Kernel.php');
        $middlewareFound = $this->fileContains("SetLocale", $appHttpKernelPath);
        if (!$middlewareFound) {
            $replaceContent = <<<'EOD'
            \App\Http\Middleware\UserLang::class,
            //glover-website-route
            \App\Http\Livewire\Extensions\GloverWebsite\Middleware\SetLocale::class,
            EOD;
            //replace the first occurance of the content
            $contentSearched = "\App\Http\Middleware\UserLang::class,";
            $this->replaceFirstOccurance($contentSearched, $replaceContent, $appHttpKernelPath);
        }

        //add to middleware group
        //\App\Http\Middleware\SetDomainTranslationNamespace::class,
        $middlewareGroupFound = $this->fileContains("SetDomainTranslationNamespace", $appHttpKernelPath);
        if (!$middlewareGroupFound) {
            $replaceContent = <<<'EOD'
            //glover-website-route
            \App\Http\Livewire\Extensions\GloverWebsite\Middleware\SetDomainTranslationNamespace::class,
            \App\Http\Middleware\TrustProxies::class,
            EOD;
            //replace the first occurance of the content
            $contentSearched = "\App\Http\Middleware\TrustProxies::class,";
            $this->replaceFirstOccurance($contentSearched, $replaceContent, $appHttpKernelPath);
        }
    }



    //
    public function handleTranslations()
    {
        //get all the .json file in the lang folder
        $langPath = __DIR__ . '/lang';
        $websitelangPath = "lang/website";
        $files = glob($langPath . '/*.json');
        //loop through the files
        foreach ($files as $file) {
            //get the content of the file
            $newLangFileContent = file_get_contents($file);
            //get the file name including the extension
            $fileName = basename($file);
            //get the corresponding lang file in the lang folder
            $langFile = resource_path("$websitelangPath/$fileName");
            //check if the file exist
            $fileExist = file_exists($langFile);
            //if the file exist
            if (!$fileExist) {
                //create the file
                $newFile = fopen($langFile, "w") or die("Unable to open file!");
                //write the content of the file
                fwrite($newFile, $newLangFileContent);
                //close the file
                fclose($newFile);
            } else {
                //get the content of the file
                $oldLangFileContent = file_get_contents($langFile);
                //merge the content of the file
                $newLangFileContent = array_merge(json_decode($oldLangFileContent, true, $flag = JSON_UNESCAPED_UNICODE), json_decode($newLangFileContent, true, $flag = JSON_UNESCAPED_UNICODE));
                //write the content of the file
                file_put_contents($langFile, json_encode($newLangFileContent, JSON_UNESCAPED_UNICODE));
            }
        }
    }


    //
    public function replaceContent($oldText, $newText, $filePath)
    {
        file_put_contents($filePath, str_replace($oldText, $newText, file_get_contents($filePath)));
    }

    public function replaceFirstOccurance($oldText, $newText, $filePath)
    {
        $fileContent = file_get_contents($filePath);
        $pos = strpos($fileContent, $oldText);
        if ($pos !== false) {
            $fileContent = substr_replace($fileContent, $newText, $pos, strlen($oldText));
            file_put_contents($filePath, $fileContent);
        }
    }

    public function fileContains($text, $filePath)
    {
        $fileContent = file_get_contents($filePath);
        return strpos($fileContent, $text);
    }
}
