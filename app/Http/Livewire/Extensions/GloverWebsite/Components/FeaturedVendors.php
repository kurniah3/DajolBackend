<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Vendor;

class FeaturedVendors extends BaseLivewireComponent
{
    public function render()
    {
        return view('livewire.extensions.glover-website.components.featured-vendors', [
            'vendors' => Vendor::where('featured', 1)->active()->get()
        ]);
    }
}