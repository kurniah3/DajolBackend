<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\Service;

class ServiceLivewire extends BaseLivewireComponent
{

    public $service;
    public $selectedGroupOptions = [];

    public function mount($id, $slug)
    {
        $this->service = Service::find($id);

        //if empty product, redirect to 404
        if (empty($this->service)) {
            return redirect()->route('glover-website.404');
        }

        //prepare the selected group option
        $this->selectedGroupOptions = [];
        foreach ($this->service->option_groups as $optionGroup) {
            if (!$optionGroup->multiple) {
                $this->selectedGroupOptions[$optionGroup->id] = null;
            } else {
                $this->selectedGroupOptions[$optionGroup->id] = [];
            }
        }
    }


    public function render()
    {
        return view('livewire.extensions.glover-website.service-details')->layout('livewire.extensions.glover-website.layouts.app');
    }


    public function bookNow()
    {
        //add selected options to session named service options
        session()->put('service_options', $this->selectedGroupOptions);
        $link = route('glover-website.service.booking', [
            'id' => $this->service->id,
            'slug' => \Str::slug($this->service->name),
        ]);
        return redirect($link);
    }
}
