<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Vendor;

class AllVendors extends BaseLivewireComponent
{

    public $vendor_type_id;
    public $title;
    public $showEmpty = false;
    public $perPage = 12;


    public function render()
    {
        $vendors =  Vendor::active()
            ->when($this->vendor_type_id, function ($query) {
                return $query->where('vendor_type_id', $this->vendor_type_id);
            })
            ->withCount('sales')
            ->orderBy('sales_count', 'DESC')
            ->paginate($this->perPage);

        //
        return view('livewire.extensions.glover-website.components.all-vendors', [
            'vendors' => $vendors
        ]);
    }

    public function loadMore()
    {
        $this->perPage = $this->perPage + 12;
    }
}
