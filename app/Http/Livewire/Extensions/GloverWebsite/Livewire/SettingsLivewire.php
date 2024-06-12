<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use GeoSot\EnvEditor\Facades\EnvEditor;

class SettingsLivewire extends BaseLivewireComponent
{

    public $websiteDomain;
    public $bannerHeight = 300;
    public $popularVendorCount = 12;
    public $campaignProductsCount = 12;
    public $languages = [];
    public $defaultLanguage = "en";

    public function mount()
    {
        $this->websiteDomain = $this->getWebsiteDomain();
        $this->bannerHeight = env('WEBSITE_BANNER_HEIGHT', 300);
        $this->popularVendorCount = env('WEBSITE_POPULAR_VENDOR_COUNT', 12);
        $this->campaignProductsCount = env('WEBSITE_CAMPAIGNS_COUNT', 12);
        if (empty($this->languages)) {
            $this->loadLanguages();
        }
    }

    public function loadLanguages()
    {
        $languagesList = config('backend.languages', []);
        $languageCodesList = config('backend.languageCodes', []);
        $this->languages = [];
        foreach ($languagesList as $key => $language) {
            $this->languages[] = [
                "id" => $languageCodesList[$key],
                "name" => $language,
            ];
        }

        $this->defaultLanguage = env('WEBSITE_DEFAULT_LANGUAGE', config('app.locale'));
    }

    public function getWebsiteDomain()
    {
        $domain = env('APP_WEBSITE_DOMAIN');
        //if the domain is not set in the env file, get the current domain
        if (!$domain) {
            $domain = request()->getHost();
            $domain = str_replace("www.", "", $domain);
            $domain = "https://{$domain}";
        }

        return $domain;
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.settings.index');
    }

    public function save()
    {
        $this->validate([
            'websiteDomain' => 'required|url',
            'bannerHeight' => 'required|numeric',
        ]);

        try {

            $this->isDemo();
            //set the domain in the env file
            $this->saveToEnv("APP_WEBSITE_DOMAIN", $this->websiteDomain);
            $this->saveToEnv("WEBSITE_DEFAULT_LANGUAGE", $this->defaultLanguage);
            $this->saveToEnv("WEBSITE_BANNER_HEIGHT", $this->bannerHeight);
            $this->saveToEnv("WEBSITE_POPULAR_VENDOR_COUNT", $this->popularVendorCount);
            $this->saveToEnv("WEBSITE_CAMPAIGNS_COUNT", $this->campaignProductsCount);
            //success
            $this->showSuccessAlert(__('Saved Successfully'));
        } catch (\Exception $e) {
            $this->showErrorAlert($e->getMessage() ?? __('Failed'));
        }
    }


    public function saveToEnv($key, $value)
    {
        if (EnvEditor::keyExists($key)) {
            EnvEditor::editKey($key, $value);
        } else {
            EnvEditor::addKey($key, $value);
        }
    }
}
