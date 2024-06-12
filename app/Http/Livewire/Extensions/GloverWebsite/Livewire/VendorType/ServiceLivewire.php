<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire\VendorType;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\VendorType;

class ServiceLivewire extends BaseLivewireComponent
{

    public $vendorType;

    public function mount($id)
    {
        $this->vendorType = VendorType::find($id);
    }

    public function render()
    {
        return view('livewire.extensions.glover-website.vendor-types.service')->layout('livewire.extensions.glover-website.layouts.app');
    }

    //
}
