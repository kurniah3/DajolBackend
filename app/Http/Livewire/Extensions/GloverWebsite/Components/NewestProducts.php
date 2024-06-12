<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Components;

use App\Http\Livewire\Extensions\GloverWebsite\Livewire\BaseLivewireComponent;
use App\Models\Product;

class NewestProducts extends BaseLivewireComponent
{

    public $vendor_type_id;
    public $title;
    public $subtitle;
    public $showEmpty = false;


    public function render()
    {
        $products =  Product::active()
            ->when($this->vendor_type_id, function ($query) {
                return $query->whereHas('vendor', function ($query) {
                    return $query->where('vendor_type_id', $this->vendor_type_id);
                });
            })
            ->orderBy('available_qty', 'DESC')
            ->orderBy('updated_at', 'DESC')
            ->paginate(env('WEBSITE_CAMPAIGNS_COUNT', 12));

        //
        return view('livewire.extensions.glover-website.components.newest-products', [
            'products' => $products
        ]);
    }
}
