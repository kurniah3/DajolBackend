<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use Illuminate\Support\Facades\Session;

class LanguageSelector extends BaseLivewireComponent
{

    public $languages = [];
    public $currentLanguage;
    public $lan = "en";
    public $link;

    public function render()
    {
        if (empty($this->languages)) {
            $this->loadLanguages();
        }
        return view('livewire.extensions.glover-website.components.language-selector');
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

        $this->lan = Session::get('locale', env('WEBSITE_DEFAULT_LANGUAGE', config('app.locale')));
        $this->currentLanguage = $this->languages[array_search($this->lan, array_column($this->languages, 'id'))]['name'] ?? $this->languages[0]['name'];
    }

    public function updatedLan($value)
    {
        $this->setNewLang($value);
    }

    public function setNewLang($lang)
    {
        try {
            //set in session
            Session::put('locale', $lang);
            $locale = Session::get('locale', env('WEBSITE_DEFAULT_LANGUAGE', config('app.locale')));
            $this->emit('reloadpage');
        } catch (\Exception $ex) {
            logger("error", [$ex]);
            \DB::rollback();
            $this->showErrorAlert($ex->getMessage() ?? __('Error updating language'));
        }
    }
}
