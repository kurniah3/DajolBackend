<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\VendorType;

class WelcomeLivewire extends BaseLivewireComponent
{


    public function render()
    {
        return view('livewire.extensions.glover-website.index', [
            'vendorTypes' => VendorType::all()
        ])->layout('livewire.extensions.glover-website.layouts.app');
    }
}