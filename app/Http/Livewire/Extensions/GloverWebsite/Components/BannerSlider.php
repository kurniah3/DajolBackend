<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Banner;

class BannerSlider extends BaseLivewireComponent
{
    public $vendor_type_id;
    public $eId = "slider";

    public function render()
    {
        return view('livewire.extensions.glover-website.components.banner-slider', [
            'banners' => Banner::when($this->vendor_type_id, function ($query) {
                return $query->whereHas('vendor', function ($query) {
                    return $query->where('vendor_type_id', $this->vendor_type_id);
                })->orwherehas('category', function ($query) {
                    return $query->where('vendor_type_id', $this->vendor_type_id);
                });
            })->active()->get()
        ]);
    }
}
