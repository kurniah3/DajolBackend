<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\Vendor;
use App\Models\Service;

class ServiceVendorLivewire extends BaseLivewireComponent
{
    public $vendor;
    public function mount($id, $slug)
    {
        $this->vendor = Vendor::find($id);
        //if empty product, redirect to 404
        if (empty($this->vendor)) {
            return redirect()->route('glover-website.404');
        }
    }


    public function render()
    {
        return view('livewire.extensions.glover-website.service-vendor-details', [
            'services' => Service::where('vendor_id', $this->vendor->id)->paginate(20),
        ])->layout('livewire.extensions.glover-website.layouts.app');
    }
}
