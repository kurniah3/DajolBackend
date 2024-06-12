<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\VendorType;

class VendorTypes extends BaseLivewireComponent
{

    public function render()
    {
        return view('livewire.extensions.glover-website.components.vendor-types', [
            'vendorTypes' => VendorType::active()->get()
        ]);
    }
}