<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\VendorType;

class VendorTypeLivewire extends BaseLivewireComponent
{

    public $vendorType;

    public function mount($id, $slug)
    {
        $this->vendorType = VendorType::where('id', $id)->where('slug', $slug)->firstOrFail();

        //if venodr type slug is taxi, redirect to taxi route
        if ($this->vendorType->slug == 'taxi') {
            return redirect()->route('glover-website.vendor.type.taxi', ['id' => $this->vendorType->id, 'slug' => $this->vendorType->slug]);
        } else if (in_array($this->vendorType->slug, ['parcel', 'package'])) {
            return redirect()->route('glover-website.vendor.type.package', ['id' => $this->vendorType->id, 'slug' => $this->vendorType->slug]);
        }
    }


    public function render()
    {
        return view('livewire.extensions.glover-website.vendor-type')->layout('livewire.extensions.glover-website.layouts.app');
    }
}
