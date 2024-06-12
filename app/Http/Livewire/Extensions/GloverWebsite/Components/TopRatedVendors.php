<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Vendor;

class TopRatedVendors extends BaseLivewireComponent
{

    public $vendor_type_id;
    public $title;
    public $subtitle;
    public $showEmpty = false;


    public function render()
    {
        return view('livewire.extensions.glover-website.components.top-rated-vendors', [
            'vendors' => Vendor::active()
                ->when($this->vendor_type_id, function ($query) {
                    return $query->where('vendor_type_id', $this->vendor_type_id);
                })
                ->withCount('sales')
                ->orderBy('sales_count', 'DESC')
                ->limit(env('WEBSITE_POPULAR_VENDOR_COUNT', 12))->get()
        ]);
    }
}
